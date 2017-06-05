<?php
namespace faywiki\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\CategoryService;
use cms\models\tables\PostsCategoriesTable;
use cms\models\tables\PostsFilesTable;
use cms\models\tables\ActionlogsTable;
use cms\services\SettingService;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\core\HttpException;
use cms\services\FlashService;
use cms\services\post\PostService;
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
            throw new HttpException('所选分类不存在');
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
            if($post_meta = WikiDocMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $post_meta;
            }
            
            //扩展信息
            if($post_extra = WikiDocExtraTable::model()->fillData($this->input->post())){
                $extra['extra'] = $post_extra;
            }
            
            //附加属性
            $extra['props'] = array(
                'data'=>$this->input->post('props', '', array()),
                'labels'=>$this->input->post('labels', 'trim', array()),
            );
            
            $doc_id = DocService::service()->create($data, $extra, $this->current_user);
            
            Response::notify('success', '文档发布成功', array('faywiki/admin/doc/edit', array(
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
        
        $this->view->render();
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
                $cats = CategoryService::service()->getChildIds($cat_id);
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
        
        $this->view->render();
    }
    
    public function edit(){
        $_setting_key = 'admin_doc_boxes';
        //这里获取enabled_boxes是为了更新文档的时候用
        //由于box可能被hook改掉，后面还会再获取一次enabled_boxes
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        $doc_id = $this->input->get('id', 'intval');
        if(empty($doc_id)){
            throw new HttpException('参数不完整', 500);
        }
        
        //原文档部分信息
        $post = WikiDocsTable::model()->find($doc_id, 'cat_id,status');
        if(!$post){
            throw new HttpException('无效的文档ID');
        }
        
        //编辑权限检查
        if(!PostService::checkEditPermission($doc_id, $this->input->post('status', 'intval'), $this->input->post('cat_id'))){
            throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
        }
        
        $cat = CategoryService::service()->get($post['cat_id'], 'title');
        
        //若分类已被删除，将文档归为根分类
        if(!$cat){
            $cat = CategoryService::service()->getByAlias('_system_wiki_doc', 'id,title');
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
            $status = $this->form()->getData('status');
            
            //未开启审核，文档却被设置为审核状态，强制修改为草稿（一般是之前开启了审核，后来关掉了）
            if(!$this->post_review && ($status == WikiDocsTable::STATUS_REVIEWED || $status == WikiDocsTable::STATUS_PENDING)){
                $this->form()->setData(array(
                    'status'=>WikiDocsTable::STATUS_DRAFT,
                ), true);
                FlashService::set('文档状态异常，被强制修改为“草稿”', 'info');
            }
            
            //筛选出文档相关字段
            $data = WikiDocsTable::model()->fillData($this->input->post());
            //发布时间特殊处理
            if(in_array('publish_time', $enabled_boxes)){
                if(empty($data['publish_time'])){
                    $data['publish_time'] = $this->current_time;
                    $data['publish_date'] = date('Y-m-d', $data['publish_time']);
                }else{
                    $data['publish_time'] = strtotime($data['publish_time']);
                    $data['publish_date'] = date('Y-m-d', $data['publish_time']);
                }
            }
            
            $extra = array();
            
            //Markdown语法特殊处理
            if($data['content_type'] == WikiDocsTable::CONTENT_TYPE_MARKDOWN){
                $extra['extra']['markdown'] = $data['content'];
                $data['content'] = $this->input->post('markdown-container-html-code');
            }
            
            //计数表
            if($post_meta = WikiDocMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $post_meta;
            }
            
            //扩展信息
            if($post_extra = WikiDocExtraTable::model()->fillData($this->input->post())){
                if(!empty($extra['extra'])){
                    $extra['extra'] = array_merge($post_extra, $extra['extra']);
                }else{
                    $extra['extra'] = $post_extra;
                }
            }
            
            //附件分类
            if(in_array('category', $enabled_boxes)){
                $extra['categories'] = $this->form()->getData('post_category', array(), 'intval');
            }
            
            //标签
            if(in_array('tags', $enabled_boxes)){
                $extra['tags'] = $this->input->post('tags', 'trim', array());
            }
            
            //附件
            if(in_array('files', $enabled_boxes)){
                $description = $this->input->post('description');
                $files = $this->input->post('files', 'intval', array());
                $extra['files'] = array();
                foreach($files as $f){
                    $extra['files'][$f] = isset($description[$f]) ? $description[$f] : '';
                }
            }
            
            //附加属性
            if(in_array('props', $enabled_boxes)){
                $extra['props'] = array(
                    'data'=>$this->input->post('props', '', array()),
                    'labels'=>$this->input->post('labels', 'trim', array()),
                );
            }
            
            PostService::service()->update($doc_id, $data, $extra);
            
            $this->actionlog(ActionlogsTable::TYPE_POST, '编辑文档', $doc_id);
            Response::notify('success', '一篇文档被编辑', false);
        }
        
        $sql = new Sql();
        $post = $sql->from(array('p'=>'posts'), WikiDocsTable::model()->getFields())
            ->joinLeft(array('pm'=>'post_meta'), 'd.id = dm.doc_id', WikiDocMetaTable::model()->formatFields('!doc_id'))
            ->joinLeft(array('pe'=>'post_extra'), 'd.id = pe.doc_id', WikiDocExtraTable::model()->formatFields('!doc_id'))
            ->where('d.id = ?', $doc_id)
            ->fetchRow()
        ;
        
        //触发事件（可以定制一些box以扩展文档功能）
        \F::event()->trigger('admin_before_doc_update', array(
            'cat_id'=>$post['cat_id'],
            'doc_id'=>$doc_id,
        ));
        
        $post['post_category'] = PostsCategoriesTable::model()->fetchCol('cat_id', array('doc_id = ?'=>$doc_id));
        
        //文档对应标签
        $tags = $sql->from(array('pt'=>'posts_tags'), '')
            ->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', 'title')
            ->where('pt.doc_id = '.$doc_id)
            ->fetchAll();
        $tags_arr = array();
        foreach($tags as $t){
            $tags_arr[] = $t['title'];
        }
        $this->form()->setData(array('tags'=>implode(',', $tags_arr)));
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');
        
        //post files
        $this->view->files = PostsFilesTable::model()->fetchAll(array(
            'doc_id = ?'=>$doc_id,
        ), 'file_id,description,is_image', 'sort');

        $this->form()->setData(array(
            'publish_time'=>date('Y-m-d H:i:s', $post['publish_time']),
            'sort'=>date('Y-m-d H:i:s', $post['sort']),
        ) + $post, true);
        $this->view->post = $post;
        
        //附加属性
        $this->view->prop_set = DocPropService::service()->getPropSet($post['id']);
        
        $cat = CategoryService::service()->get($post['cat_id'], 'title');
        $this->layout->subtitle = '编辑文档- 所属分类：'.$cat['title'];
        if($this->checkPermission('faywiki/admin/doc/create')){
            $this->layout->sublink = array(
                'uri'=>array('faywiki/admin/doc/create', array(
                    'cat_id'=>$post['cat_id'],
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
        
        $this->view->render();
    }
    
    /**
     * 删除
     */
    public function delete(){
        $doc_id = $this->input->get('id', 'intval');
        
        PostService::service()->delete($doc_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文档移入回收站', $doc_id);
        
        Response::notify('success', array(
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
        
        if(!PostService::checkUndeletePermission($doc_id)){
            throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
        }
        PostService::service()->undelete($doc_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文档移出回收站', $doc_id);
        
        Response::notify('success', array(
            'message'=>'一篇文档被还原',
            'id'=>$doc_id,
        ));
    }
    
    public function remove(){
        $doc_id = $this->input->get('id', 'intval');
        
        DocService::service()->remove($doc_id);
        
        Response::notify('success', array(
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
        
        $this->view->renderPartial('_box_props', array(
            'prop_set'=>$prop_set,
        ));
    }
    
    /**
     * 分类管理
     */
    public function cat(){
        $this->layout->current_directory = 'post';
        $this->layout->_setting_panel = '_setting_cat';
        
        //页面设置
        $this->settingForm('admin_doc_cat', '_setting_cat', array(
            'default_dep'=>2,
        ));
        
        $this->layout->subtitle = '文档分类';
        $this->view->cats = CategoryService::service()->getTree('_system_wiki_doc');
        $root_node = CategoryService::service()->getByAlias('_system_wiki_doc', 'id');
        $this->view->root = $root_node['id'];
        
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
        
        $this->view->render();
    }
    
    public function batch(){
        $ids = $this->input->post('ids', 'intval');
        $action = $this->input->post('batch_action');
        
        switch($action){
            case 'set-published':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchPublish($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被发布');
                Response::notify('success', count($affected_rows) . '篇文档被发布');
            break;
            case 'set-draft':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchDraft($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“草稿”');
                Response::notify('success', count($affected_rows) . '篇文档被标记为“草稿”');
            break;
            case 'set-pending':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchPending($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“待审核”');
                Response::notify('success', count($affected_rows) . '篇文档被标记为“待审核”');
            break;
            case 'set-reviewed':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, WikiDocsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchReviewed($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被标记为“通过审核”');
                Response::notify('success', count($affected_rows) . '篇文档被标记为“通过审核”');
            break;
            case 'delete':
                foreach($ids as $id){
                    if(!PostService::checkDeletePermission($id)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchDelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被移入回收站');
                Response::notify('success', count($affected_rows) . '篇文档被移入回收站');
            break;
            case 'undelete':
                foreach($ids as $id){
                    if(!PostService::checkUndeletePermission($id)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchUndelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被还原');
                Response::notify('success', count($affected_rows) . '篇文档被还原');
            break;
            case 'remove':
                foreach($ids as $id){
                    if(!PostService::checkRemovePermission($id)){
                        throw new HttpException('您无权限编辑该文档', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = array();
                foreach($ids as $id){
                    if(PostService::service()->remove($id)){
                        $affected_rows[] = $id;
                    }
                }

                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文档' . json_encode($affected_rows) . '被永久删除');
                Response::notify('success', count($affected_rows) . '篇文档被永久删除');
            break;
            default:
                Response::notify('error', array(
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
            'all'=>PostService::service()->getCount(),
            'published'=>PostService::service()->getCount(WikiDocsTable::STATUS_PUBLISHED),
            'draft'=>PostService::service()->getCount(WikiDocsTable::STATUS_DRAFT),
            'deleted'=>PostService::service()->getDeletedCount(),
        );
        
        if($this->post_review){
            $data['pending'] = PostService::service()->getCount(WikiDocsTable::STATUS_PENDING);
            $data['reviewed'] = PostService::service()->getCount(WikiDocsTable::STATUS_REVIEWED);
        }
        
        Response::json($data);
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
            Response::json(array(
                'cats'=>CategoryService::service()->getTree('_system_wiki_doc'),
            ));
        }else if($format == 'list'){
            Response::json(array(
                'cats'=>CategoryService::service()->getChildren('_system_wiki_doc'),
            ));
        }
    }

    /**
     * 后台预览一篇文档（非前端主题式样预览）
     */
    public function preview(){
        //表单验证
        $this->form()->setScene('final')
            ->setRules(array(
                array(array('id'), 'required'),
                array(array('id'), 'int', array('min'=>1)),
            ))->setFilters(array(
                'id'=>'intval',
            ))->setLabels(array(
                'id'=>'文档ID',
            ))->check();
        
        $post = PostService::service()->get(
            $this->form()->getData('id'),
            array(
                'post'=>array(
                    'fields'=>array('*'),
                    'extra'=>array(
                        'thumbnail'=>'0x200'
                    )
                ),
                'category'=>array(
                    'fields'=>array('title')
                ),
                'files'=>array(
                    'fields'=>array('id', 'url', 'thumbnail', 'description', 'is_image'),
                    'extra'=>array(
                        'thumbnail'=>'200x130'
                    )
                ),
                'user'=>array(
                    'fields'=>array('nickname', 'avatar')
                )
            ),
            null,
            false
        );
        if(!$post){
            throw new HttpException("指定文档ID[{$this->form()->getData('id')}]不存在");
        }
        
        $this->view->renderPartial(null, array(
            'post'=>$post
        ));
    }
}