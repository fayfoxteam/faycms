<?php
namespace faywiki\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\CategoriesTable;
use cms\services\CategoryService;
use cms\services\FlashService;
use cms\services\post\PostService;
use cms\services\SettingService;
use fay\common\ListView;
use fay\exceptions\AccessDeniedHttpException;
use fay\exceptions\RecordNotFoundException;
use fay\exceptions\ValidationException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;
use faywiki\models\tables\WikiDocExtraTable;
use faywiki\models\tables\WikiDocMetaTable;
use faywiki\models\tables\WikiDocsTable;
use faywiki\services\doc\DocPropService;
use faywiki\services\doc\DocService;

class DocController extends AdminController{
    /**
     * box列表
     */
    public $boxes = array(
        array('name'=>'views', 'title'=>'阅读数'),
        array('name'=>'likes', 'title'=>'点赞数'),
        array('name'=>'category', 'title'=>'分类'),
        array('name'=>'thumbnail', 'title'=>'缩略图'),
        array('name'=>'seo', 'title'=>'SEO优化'),
        array('name'=>'abstract', 'title'=>'摘要'),
        array('name'=>'props', 'title'=>'附加属性'),
        array('name'=>'history', 'title'=>'历史版本'),
    );
    
    /**
     * 默认box排序
     */
    public $default_box_sort = array(
        'side'=>array(
            'thumbnail', 'category', 'views', 'likes', 'history'
        ),
        'normal'=>array(
            'abstract', 'props', 'seo'
        ),
    );
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'wiki';
    }
    
    public function create(){
        $cat_id = $this->input->get('cat_id', 'intval');
        $cat_id || $cat_id = CategoryService::service()->getIdByAlias('_system_wiki_doc');
        $cat = CategoryService::service()->get($cat_id, 'title', '_system_wiki_doc');
        
        if(!$cat){
            throw new RecordNotFoundException("所选分类[{$cat_id}]不存在");
        }
        
        //触发事件（可以定制一些box以扩展文档功能）
        \F::event()->trigger('admin_before_doc_create', array(
            'cat_id'=>$cat_id,
        ));
        
        $this->form()->setModel(WikiDocsTable::model())
            ->setModel(WikiDocExtraTable::model())
            ->setModel(WikiDocMetaTable::model());
        if($this->input->post() && $this->form()->check()){
            //添加docs表
            $data = WikiDocsTable::model()->fillData($this->input->post());
            isset($data['cat_id']) || $data['cat_id'] = $cat_id;
            
            $extra = array();
            
            //Meta信息
            if($doc_meta = WikiDocMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $doc_meta;
            }
            
            //扩展信息
            if($doc_extra = WikiDocExtraTable::model()->fillData($this->input->post())){
                $extra['extra'] = $doc_extra;
            }
            
            //附加属性
            $extra['props'] = array(
                'data'=>$this->input->post('props', '', array()),
                'labels'=>$this->input->post('labels', 'trim', array()),
            );
            
            $doc_id = DocService::service()->create($data, $extra, $this->current_user);
            
            Response::notify(Response::NOTIFY_SUCCESS, '文档发布成功', array('faywiki/admin/doc/edit', array(
                'id'=>$doc_id,
            )));
        }
        
        //设置附加属性
        $this->view->prop_set = DocPropService::service()->getPropsByCatId($cat_id);
        
        $this->form()->setData(array(
            'cat_id'=>$cat_id,
        ));
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_doc_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $_setting_key = 'admin_doc_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        //所有文档分类
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');
        
        //标题
        if(in_array('main_category', $enabled_boxes)){
            $this->layout->subtitle = '新增文档';
        }else{
            //若没有给出分类选择框，则在标题中显示分类名
            $this->layout->subtitle = '新增文档 - 所属分类：'.$cat['title'];
        }
        
        if($this->checkPermission('faywiki/admin/doc/index')){
            $this->layout->sublink = array(
                'uri'=>array('faywiki/admin/doc/index'),
                'text'=>'所有文档',
            );
        }
        
        $this->layout->_help_panel = '_help';
        
        return $this->view->render();
    }

    /**
     * 文档列表
     * @parameter int $status 文档状态筛选
     * @parameter int $deleted 若为1，列出回收站内文档；若为0，列出非回收站内文档；默认为0
     * @parameter field $time_field 根据指定字段进行时间段筛选，
     * @parameter 
     */
    public function index(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('status', 'range', array(
                'range'=>array(
                    WikiDocsTable::STATUS_PUBLISHED, WikiDocsTable::STATUS_PENDING,
                    WikiDocsTable::STATUS_DRAFT,
                ),
            )),
            array('deleted', 'range', array(
                'range'=>array(0, 1),
            )),
            array('time_field', 'range', array(
                'range'=>array('create_time', 'update_time')
            )),
            array(array('start_time', 'end_time'), 'datetime'),
            array('orderby', 'range', array(
                'range'=>WikiDocsTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('keywords_field', 'range', array(
                'range'=>WikiDocsTable::model()->getFields(),
            )),
            array('cat_id', 'int', array('min'=>1))
        ))->check();
        
        $this->layout->subtitle = '所有文档';
        
        $cat_id = $this->input->get('cat_id', 'intval', 0);
        
        //权限检查
        if($this->checkPermission('faywiki/admin/doc/create')){
            $this->layout->sublink = array(
                'uri'=>array('faywiki/admin/doc/create', array(
                    'cat_id'=>$cat_id
                )),
                'text'=>'新增文档',
            );
        }
        
        //页面设置
        $_settings = $this->settingForm('admin_doc_index', '_setting_index', array(
            'cols'=>array('category', 'status', 'update_time', 'create_time'),
            'display_name'=>'username',
            'display_time'=>'short',
            'page_size'=>10,
        ));
        
        $sql = new Sql();
        $sql->from(array('d'=>WikiDocsTable::model()->getTableName()), WikiDocsTable::model()->formatFields('!content'))
            ->joinLeft(array('dm'=>WikiDocMetaTable::model()->getTableName()), 'd.id = dm.doc_id', WikiDocMetaTable::model()->formatFields('!doc_id'));
        
        if(in_array('category', $_settings['cols'])){
            $sql->joinLeft(array('c'=>'categories'), 'd.cat_id = c.id', 'title AS cat_title');
        }
        
        if(in_array('user', $_settings['cols'])){
            $sql->joinLeft(array('u'=>'users'), 'd.user_id = u.id', 'username,nickname,realname');
        }
        
        //根据分类搜索
        if($cat_id){
            if($this->input->get('with_child')){
                //包含子分类搜索
                $cats = CategoryService::service()->getChildrenIDs($cat_id);
                //根据文档分类搜索
                $orWhere = array(
                    'd.cat_id = ?'=>$cat_id,
                );
                foreach($cats as $c){
                    $orWhere[] = "d.cat_id = {$c}";
                }
                $sql->orWhere($orWhere);
            }else{
                //根据文档分类搜索
                $sql->where(array('d.cat_id = ?'=>$cat_id));
            }
        }
        
        //文档状态
        if($this->input->get('deleted', 'intval') == 1){
            $sql->where('d.delete_time > 0');
        }else if($this->input->get('status') !== null && $this->input->get('deleted', 'intval') != 1){
            $sql->where(array(
                'd.delete_time = 0',
                'd.status = ?'=>$this->input->get('status', 'intval'),
            ));
        }else{
            $sql->where('d.delete_time = 0');
        }
        
        //时间段
        if($this->input->get('start_time')){
            $sql->where(array("d.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time', 'strtotime')));
        }
        if($this->input->get('end_time')){
            $sql->where(array("d.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time', 'strtotime')));
        }
        
        //关键词搜索
        if($this->input->get('keywords')){
            if(in_array($this->input->get('keywords_field'), array('title'))){
                $sql->where(array("d.{$this->input->get('keywords_field')} LIKE ?"=>'%'.$this->input->get('keywords').'%'));
            }else if(in_array($this->input->get('keywords_field'), array('id', 'user_id'))){
                $sql->where(array("d.{$this->input->get('keywords_field')} = ?"=>$this->input->get('keywords', 'intval')));
            }
        }
        
        //排序
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("d.{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('d.id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
        ));

        //列表页会根据enabled_boxes决定是否显示某些列
        $this->view->enabled_boxes = $this->getEnabledBoxes('admin_doc_boxes');
        //查找文档分类
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');
        
        return $this->view->render();
    }
    
    public function edit(){
        $_setting_key = 'admin_doc_boxes';
        //这里获取enabled_boxes是为了更新文档的时候用
        //由于box可能被hook改掉，后面还会再获取一次enabled_boxes
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        $doc_id = $this->input->get('id', 'intval');
        if(empty($doc_id)){
            throw new ValidationException('id参数不能为空');
        }
        
        //原文档部分信息
        $doc = WikiDocsTable::model()->find($doc_id, 'cat_id,status');
        if(!$doc){
            throw new RecordNotFoundException("无效的文档ID[{$doc_id}]");
        }
        
        $cat = CategoryService::service()->get($doc['cat_id'], 'title');
        
        //若分类已被删除，将文档归为根分类
        if(!$cat){
            $cat = CategoryService::service()->get('_system_wiki_doc', 'id,title');
            WikiDocsTable::model()->update(array(
                'cat_id'=>$cat['id'],
            ), $doc_id);
            FlashService::set('文档所属分类不存在，请重新设置文档分类', 'info');
        }
        
        $this->form()->setModel(WikiDocsTable::model())
            ->setModel(WikiDocExtraTable::model())
            ->setModel(WikiDocMetaTable::model())
        ;
        
        if($this->input->post() && $this->form()->check()){
            //筛选出文档相关字段
            $data = WikiDocsTable::model()->fillData($this->input->post());
            //发布时间特殊处理
            $extra = array();
            
            //计数表
            if($doc_meta = WikiDocMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $doc_meta;
            }
            
            //扩展信息
            if($doc_extra = WikiDocExtraTable::model()->fillData($this->input->post())){
                $extra['extra'] = $doc_extra;
            }
            
            //附加属性
            if(in_array('props', $enabled_boxes)){
                $extra['props'] = array(
                    'data'=>$this->input->post('props', '', array()),
                    'labels'=>$this->input->post('labels', 'trim', array()),
                );
            }
            
            DocService::service()->update($doc_id, $data, $extra);
            
            $this->actionlog(ActionlogsTable::TYPE_POST, '编辑文档', $doc_id);
            Response::notify(Response::NOTIFY_SUCCESS, '一篇文档被编辑', false);
        }
        
        $sql = new Sql();
        $doc = $sql->from(array('d'=>WikiDocsTable::model()->getTableName()))
            ->joinLeft(array('dm'=>WikiDocMetaTable::model()->getTableName()), 'd.id = dm.doc_id', WikiDocMetaTable::model()->formatFields('!doc_id'))
            ->joinLeft(array('de'=>WikiDocExtraTable::model()->getTableName()), 'd.id = de.doc_id', WikiDocExtraTable::model()->formatFields('!doc_id'))
            ->where('d.id = ?', $doc_id)
            ->fetchRow()
        ;
        
        //触发事件（可以定制一些box以扩展文档功能）
        \F::event()->trigger('admin_before_doc_update', array(
            'cat_id'=>$doc['cat_id'],
            'doc_id'=>$doc_id,
        ));
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');

        $this->form()->setData($doc, true);
        $this->view->doc = $doc;
        
        //附加属性
        $this->view->prop_set = DocPropService::service()->getPropSet($doc['id']);
        
        $cat = CategoryService::service()->get($doc['cat_id'], 'title');
        $this->layout->subtitle = '编辑文档- 所属分类：'.$cat['title'];
        if($this->checkPermission('faywiki/admin/doc/create')){
            $this->layout->sublink = array(
                'uri'=>array('faywiki/admin/doc/create', array(
                    'cat_id'=>$doc['cat_id'],
                )),
                'text'=>'在此分类下发布文档',
            );
        }
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_doc_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        return $this->view->render();
    }
    
    /**
     * 删除
     */
    public function delete(){
        $doc_id = $this->input->get('id', 'intval');
        
        DocService::service()->delete($doc_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文档移入回收站', $doc_id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一篇文档被移入回收站 - '.HtmlHelper::link('撤销', array('faywiki/admin/doc/undelete', array(
                'id'=>$doc_id,
            ))),
            'id'=>$doc_id,
        ));
    }
    
    /**
     * 还原
     */
    public function undelete(){
        $doc_id = $this->input->get('id', 'intval');
        
        DocService::service()->undelete($doc_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文档移出回收站', $doc_id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一篇文档被还原',
            'id'=>$doc_id,
        ));
    }
    
    public function remove(){
        $doc_id = $this->input->get('id', 'intval');
        
        DocService::service()->remove($doc_id);
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一篇文档被永久删除',
            'id'=>$doc_id,
        ));
    }
    
    /**
     * 单独渲染一个prop box
     */
    public function getPropBox(){
        $cat_id = $this->input->get('cat_id', 'intval');
        $doc_id = $this->input->get('doc_id', 'intval');
        
        //文档对应附加属性值
        $props = DocPropService::service()->getPropsByCatId($cat_id);
        if(!empty($props) && $doc_id){
            $prop_set = DocPropService::service()->getPropSet($doc_id, $props);
        }else{
            $prop_set = $props;
        }
        
        echo $this->view->renderPartial('_box_props', array(
            'prop_set'=>$prop_set,
        ));
    }
    
    /**
     * 分类管理
     */
    public function cat(){
        $this->layout->current_directory = 'wiki';
        $this->layout->_setting_panel = '_setting_cat';
        
        //页面设置
        $this->settingForm('admin_doc_cat', '_setting_cat', array(
            'default_dep'=>2,
        ));
        
        $this->layout->subtitle = '文档分类';
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');
        $root_node = CategoryService::service()->get('_system_wiki_doc', 'id');
        $this->view->root = $root_node['id'];

        \F::form('create')->setModel(CategoriesTable::model());
        \F::form('edit')->setModel(CategoriesTable::model());
        
        if($this->checkPermission('faywiki/admin/doc/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加文档分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'百科文档',
                    'data-id'=>$root_node['id'],
                ),
            );
        }
        
        return $this->view->render();
    }
    
    public function batch(){
        $ids = $this->input->post('ids', 'intval');
        $action = $this->input->post('batch_action');
        
        switch($action){
            case 'set-published':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchPublish($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被发布');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被发布');
            break;
            case 'set-draft':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchDraft($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“草稿”');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被标记为“草稿”');
            break;
            case 'set-pending':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchPending($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“待审核”');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被标记为“待审核”');
            break;
            case 'set-reviewed':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchReviewed($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“通过审核”');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被标记为“通过审核”');
            break;
            case 'delete':
                foreach($ids as $id){
                    if(!PostService::checkDeletePermission($id)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchDelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被移入回收站');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被移入回收站');
            break;
            case 'undelete':
                foreach($ids as $id){
                    if(!PostService::checkUndeletePermission($id)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = PostService::service()->batchUndelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被还原');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被还原');
            break;
            case 'remove':
                foreach($ids as $id){
                    if(!PostService::checkRemovePermission($id)){
                        throw new AccessDeniedHttpException('您无权限编辑该文档');
                    }
                }
                
                $affected_rows = array();
                foreach($ids as $id){
                    if(PostService::service()->remove($id)){
                        $affected_rows[] = $id;
                    }
                }

                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被永久删除');
                Response::notify(Response::NOTIFY_SUCCESS, count($affected_rows) . '篇文档被永久删除');
            break;
            default:
                Response::notify(Response::NOTIFY_FAIL, array(
                    'message'=>'操作选项不能为空',
                    'error_code'=>'action-can-not-be-empty',
                ));
            break;
        }
    }
    
    /**
     * 返回各状态下的文档数
     */
    public function getCounts(){
        $data = array(
            'all'=>DocService::service()->getCount(),
            'published'=>DocService::service()->getCount(WikiDocsTable::STATUS_PUBLISHED),
            'draft'=>DocService::service()->getCount(WikiDocsTable::STATUS_DRAFT),
            'pending'=>DocService::service()->getCount(WikiDocsTable::STATUS_PENDING),
            'deleted'=>DocService::service()->getDeletedCount(),
        );
        
        return Response::json($data);
    }

    /**
     * 获取可用的文档分类
     */
    public function getCats(){
        $format = $this->input->request('format', 'trim', 'html');

        if($format == 'html'){
            //以html select标签的形式输出可用分类
            echo $this->form()->select('cat_id', HtmlHelper::getSelectOptions(
                CategoryService::service()->getTree('_system_wiki_doc')
            ), array(
                'class'=>'form-control'
            ));
        }else if($format == 'tree'){
            return Response::json(array(
                'cats'=>CategoryService::service()->getTree('_system_wiki_doc'),
            ));
        }else if($format == 'list'){
            return Response::json(array(
                'cats'=>CategoryService::service()->getChildren('_system_wiki_doc'),
            ));
        }
    }
}