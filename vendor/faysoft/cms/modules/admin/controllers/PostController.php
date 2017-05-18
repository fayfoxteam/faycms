<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\CategoryService;
use cms\services\post\PostPropService;
use cms\models\tables\PostsTable;
use cms\models\tables\PostsCategoriesTable;
use cms\models\tables\PostsFilesTable;
use cms\models\tables\ActionlogsTable;
use cms\services\SettingService;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\core\HttpException;
use cms\services\OptionService;
use cms\services\FlashService;
use cms\models\tables\PostMetaTable;
use cms\services\post\PostService;
use cms\models\tables\PostExtraTable;

class PostController extends AdminController{
    /**
     * 是否启用文章审核功能
     */
    public $post_review = false;
    
    /**
     * 是否启用分类权限（根据角色分配可编辑的分类）
     */
    public $role_cats = false;
    
    /**
     * box列表
     */
    public $boxes = array(
        array('name'=>'alias', 'title'=>'别名'),
        array('name'=>'views', 'title'=>'阅读数'),
        array('name'=>'likes', 'title'=>'点赞数'),
        array('name'=>'publish_time', 'title'=>'发布时间'),
        array('name'=>'main_category', 'title'=>'主分类'),
        array('name'=>'category', 'title'=>'附加分类'),
        array('name'=>'thumbnail', 'title'=>'缩略图'),
        array('name'=>'seo', 'title'=>'SEO优化'),
        array('name'=>'abstract', 'title'=>'摘要'),
        array('name'=>'tags', 'title'=>'标签'),
        array('name'=>'files', 'title'=>'附件'),
        array('name'=>'props', 'title'=>'附加属性'),
        array('name'=>'gather', 'title'=>'采集器'),
        array('name'=>'source', 'title'=>'来源')
    );
    
    /**
     * 默认box排序
     */
    public $default_box_sort = array(
        'side'=>array(
            'publish_time', 'thumbnail', 'main_category', 'views', 'likes', 'alias', 'props', 'gather', 'history', 'source'
        ),
        'normal'=>array(
            'abstract', 'tags', 'files', 'seo'
        ),
    );
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'post';
        $this->post_review = !!(OptionService::get('system:post_review'));
        $this->role_cats = !!(OptionService::get('system:post_role_cats'));

        if(OptionService::get('system:save_post_history')){
            //只有当开启历史记录时，才显示历史记录box
            $this->boxes[] = array('name'=>'history', 'title'=>'历史版本');
        }
    }
    
    public function create(){
        $cat_id = $this->input->get('cat_id', 'intval');
        $cat_id || $cat_id = CategoryService::service()->getIdByAlias('_system_post');
        $cat = CategoryService::service()->get($cat_id, 'title', '_system_post');
        
        if(!$cat){
            throw new HttpException('所选分类不存在');
        }
        
        //触发事件（可以定制一些box以扩展文章功能）
        \F::event()->trigger('admin_before_post_create', array(
            'cat_id'=>$cat_id,
        ));
        
        //先把可用boxes获取出来，post逻辑中要用到
        $_setting_key = 'admin_post_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        $this->form()->setModel(PostsTable::model())
            ->setModel(PostExtraTable::model())
            ->setModel(PostsFilesTable::model())
            ->setModel(PostMetaTable::model());
        if($this->input->post() && $this->form()->check()){
            //添加posts表
            $data = PostsTable::model()->fillData($this->input->post());
            isset($data['cat_id']) || $data['cat_id'] = $cat_id;
            
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
            if(isset($data['content_type']) && $data['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
                $extra['extra']['markdown'] = $data['content'];
                $data['content'] = $this->input->post('markdown-container-html-code');
            }
            
            //Meta信息
            if($post_meta = PostMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $post_meta;
            }
            
            //扩展信息
            if($post_extra = PostExtraTable::model()->fillData($this->input->post())){
                if(isset($extra['extra'])){
                    $extra['extra'] = array_merge($post_extra, $extra['extra']);
                }else{
                    $extra['extra'] = $post_extra;
                }
            }
            
            //附加分类
            if($post_category = $this->form()->getData('post_category')){
                $extra['categories'] = $post_category;
            }
            
            //标签
            if($tags = $this->input->post('tags')){
                $extra['tags'] = $tags;
            }
            
            //附件
            $description = $this->input->post('description');
            $files = $this->input->post('files', 'intval', array());
            $extra['files'] = array();
            foreach($files as $f){
                $extra['files'][$f] = isset($description[$f]) ? $description[$f] : '';
            }
            
            //附加属性
            $extra['props'] = $this->input->post('props', '', array());
            
            $post_id = PostService::service()->create($data, $extra, $this->current_user);
            
            $this->actionlog(ActionlogsTable::TYPE_POST, '添加文章', $post_id);
            Response::notify('success', '文章发布成功', array('cms/admin/post/edit', array(
                'id'=>$post_id,
            )));
        }
        
        //设置附加属性
        $this->view->prop_set = PostPropService::service()->getPropsByCatId($cat_id);
        
        $this->form()->setData(array(
            'cat_id'=>$cat_id,
        ));
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_post_box_sort');
        $_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
        $this->view->_box_sort_settings = $_box_sort_settings;
        
        //页面设置
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_edit', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        //所有文章分类
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        
        //标题
        if(in_array('main_category', $enabled_boxes)){
            $this->layout->subtitle = '撰写文章';
        }else{
            //若没有给出主分类选择框，则在标题中显示分类名
            $this->layout->subtitle = '撰写文章 - 所属分类：'.$cat['title'];
        }
        
        if($this->checkPermission('cms/admin/post/index')){
            $this->layout->sublink = array(
                'uri'=>array('cms/admin/post/index'),
                'text'=>'所有文章',
            );
        }
        
        $this->layout->_help_panel = '_help';
        
        $this->view->render();
    }

    /**
     * 文章列表
     * @parameter int $status 文章状态筛选
     * @parameter int $deleted 若为1，列出回收站内文章；若为0，列出非回收站内文章；默认为0
     * @parameter field $time_field 根据指定字段进行时间段筛选，
     * @parameter 
     */
    public function index(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('status', 'range', array(
                'range'=>array(
                    PostsTable::STATUS_PUBLISHED, PostsTable::STATUS_DRAFT,
                    PostsTable::STATUS_REVIEWED, PostsTable::STATUS_PENDING
                ),
            )),
            array('deleted', 'range', array(
                'range'=>array(0, 1),
            )),
            array('time_field', 'range', array(
                'range'=>array('publish_time', 'create_time', 'update_time')
            )),
            array(array('start_time', 'end_time'), 'datetime'),
            array('orderby', 'range', array(
                'range'=>PostsTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('keywords_field', 'range', array(
                'range'=>PostsTable::model()->getFields(),
            )),
            array('cat_id', 'int', array('min'=>1))
        ))->check();
        
        $this->layout->subtitle = '所有文章';
        
        $cat_id = $this->input->get('cat_id', 'intval', 0);
        
        //权限检查
        if($this->checkPermission('cms/admin/post/create')){
            $this->layout->sublink = array(
                'uri'=>array('cms/admin/post/create', array(
                    'cat_id'=>$cat_id
                )),
                'text'=>'撰写文章',
            );
        }
        
        //页面设置
        $_settings = $this->settingForm('admin_post_index', '_setting_index', array(
            'cols'=>array('main_category', 'status', 'publish_time', 'update_time', 'create_time', 'sort'),
            'display_name'=>'username',
            'display_time'=>'short',
            'page_size'=>10,
        ));
        
        $sql = new Sql();
        $count_sql = new Sql();//逻辑太复杂，靠通用逻辑从完整sql中替换出来的话，效率太低
        $sql->from(array('p'=>'posts'), PostsTable::model()->formatFields('!content'))
            ->joinLeft(array('pm'=>'post_meta'), 'p.id = pm.post_id', PostMetaTable::model()->formatFields('!post_id'));
        $count_sql->from(array('p'=>'posts'), 'COUNT(*)');
        
        if(in_array('main_category', $_settings['cols'])){
            $sql->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title');
        }
        
        if(in_array('user', $_settings['cols'])){
            $sql->joinLeft(array('u'=>'users'), 'p.user_id = u.id', 'username,nickname,realname');
        }
        
        //根据分类搜索
        if($cat_id){
            if($this->input->get('with_child')){
                //包含子分类搜索
                $cats = CategoryService::service()->getChildIds($cat_id);
                if($this->input->get('with_slave')){
                    $orWhere = array(
                        "p.cat_id = {$cat_id}",
                        "pc.cat_id = {$cat_id}",
                    );
                    foreach($cats as $c){
                        $orWhere[] = "p.cat_id = {$c}";
                        $orWhere[] = "pc.cat_id = {$c}";
                    }
                    //包含文章从分类搜索
                    $sql->joinLeft(array('pc'=>'posts_categories'), 'p.id = pc.post_id')
                        ->orWhere($orWhere)
                        ->distinct(true);
                    $count_sql->joinLeft(array('pc'=>'posts_categories'), 'p.id = pc.post_id')
                        ->orWhere($orWhere)
                        ->countBy('DISTINCT p.id')
                    ;
                }else{
                    //仅根据文章主分类搜索
                    $orWhere = array(
                        "p.cat_id = {$cat_id}",
                    );
                    foreach($cats as $c){
                        $orWhere[] = "p.cat_id = {$c}";
                    }
                    $sql->orWhere($orWhere);
                    $count_sql->orWhere($orWhere);
                }
            }else{
                if($this->input->get('with_slave')){
                    //包含文章从分类搜索
                    $sql->where(array('p.cat_id = ?'=>$cat_id));
                    $count_sql->where(array('p.cat_id = ?'=>$cat_id));
                }else{
                    //仅根据文章主分类搜索
                    $sql->where(array('p.cat_id = ?'=>$cat_id));
                    $count_sql->where(array('p.cat_id = ?'=>$cat_id));
                }
            }
        }
        
        //文章状态
        if($this->input->get('deleted', 'intval') == 1){
            $sql->where('p.delete_time > 0');
            $count_sql->where('p.delete_time > 0');
        }else if($this->input->get('status') !== null && $this->input->get('deleted', 'intval') != 1){
            $sql->where(array(
                'p.delete_time = 0',
                'p.status = ?'=>$this->input->get('status', 'intval'),
            ));
            $count_sql->where(array(
                'p.delete_time = 0',
                'p.status = ?'=>$this->input->get('status', 'intval'),
            ));
        }else{
            $sql->where('p.delete_time = 0');
            $count_sql->where('p.delete_time = 0');
        }
        
        //时间段
        if($this->input->get('start_time')){
            $sql->where(array("p.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time', 'strtotime')));
            $count_sql->where(array("p.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time', 'strtotime')));
        }
        if($this->input->get('end_time')){
            $sql->where(array("p.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time', 'strtotime')));
            $count_sql->where(array("p.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time', 'strtotime')));
        }
        
        if($tag_id = $this->input->get('tag_id', 'intval')){
            $sql->joinLeft(array('pt'=>'posts_tags'), 'p.id = pt.post_id')
                ->where(array(
                    'pt.tag_id = ?'=>$tag_id,
                ))
                ->distinct(true);
            $count_sql->joinLeft(array('pt'=>'posts_tags'), 'p.id = pt.post_id')
                ->where(array(
                    'pt.tag_id = ?'=>$tag_id,
                ))
                ->countBy('DISTINCT p.id');
        }
        
        //关键词搜索
        if($this->input->get('keywords')){
            if(in_array($this->input->get('keywords_field'), array('title'))){
                $sql->where(array("p.{$this->input->get('keywords_field')} LIKE ?"=>'%'.$this->input->get('keywords').'%'));
                $count_sql->where(array("p.{$this->input->get('keywords_field')} LIKE ?"=>'%'.$this->input->get('keywords').'%'));
            }else if(in_array($this->input->get('keywords_field'), array('id', 'user_id'))){
                $sql->where(array("p.{$this->input->get('keywords_field')} = ?"=>$this->input->get('keywords', 'intval')));
                $count_sql->where(array("p.{$this->input->get('keywords_field')} = ?"=>$this->input->get('keywords', 'intval')));
            }
        }
        
        //排序
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("P.{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('p.id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
            'count_sql'=>$count_sql,
        ));

        //列表页会根据enabled_boxes决定是否显示某些列
        $this->view->enabled_boxes = $this->getEnabledBoxes('admin_post_boxes');
        //查找文章分类
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        
        $this->view->render();
    }
    
    public function edit(){
        $_setting_key = 'admin_post_boxes';
        //这里获取enabled_boxes是为了更新文章的时候用
        //由于box可能被hook改掉，后面还会再获取一次enabled_boxes
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        
        $post_id = $this->input->get('id', 'intval');
        if(empty($post_id)){
            throw new HttpException('参数不完整', 500);
        }
        
        //原文章部分信息
        $post = PostsTable::model()->find($post_id, 'cat_id,status');
        if(!$post){
            throw new HttpException('无效的文章ID');
        }
        
        //编辑权限检查
        if(!PostService::checkEditPermission($post_id, $this->input->post('status', 'intval'), $this->input->post('cat_id'))){
            throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
        }
        
        $cat = CategoryService::service()->get($post['cat_id'], 'title');
        
        //若分类已被删除，将文章归为根分类
        if(!$cat){
            $cat = CategoryService::service()->getByAlias('_system_post', 'id,title');
            PostsTable::model()->update(array(
                'cat_id'=>$cat['id'],
            ), $post_id);
            FlashService::set('文章所属分类不存在，请重新设置文章分类', 'info');
        }
        
        $this->form()->setModel(PostsTable::model())
            ->setModel(PostExtraTable::model())
            ->setModel(PostMetaTable::model())
            ->setModel(PostsFilesTable::model());
        
        if($this->input->post() && $this->form()->check()){
            $status = $this->form()->getData('status');
            
            //未开启审核，文章却被设置为审核状态，强制修改为草稿（一般是之前开启了审核，后来关掉了）
            if(!$this->post_review && ($status == PostsTable::STATUS_REVIEWED || $status == PostsTable::STATUS_PENDING)){
                $this->form()->setData(array(
                    'status'=>PostsTable::STATUS_DRAFT,
                ), true);
                FlashService::set('文章状态异常，被强制修改为“草稿”', 'info');
            }
            
            //筛选出文章相关字段
            $data = PostsTable::model()->fillData($this->input->post());
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
            if($data['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
                $extra['extra']['markdown'] = $data['content'];
                $data['content'] = $this->input->post('markdown-container-html-code');
            }
            
            //计数表
            if($post_meta = PostMetaTable::model()->fillData($this->input->post())){
                $extra['meta'] = $post_meta;
            }
            
            //扩展信息
            if($post_extra = PostExtraTable::model()->fillData($this->input->post())){
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
                $extra['props'] = $this->input->post('props');
            }
            
            PostService::service()->update($post_id, $data, $extra);
            
            $this->actionlog(ActionlogsTable::TYPE_POST, '编辑文章', $post_id);
            Response::notify('success', '一篇文章被编辑', false);
        }
        
        $sql = new Sql();
        $post = $sql->from(array('p'=>'posts'), PostsTable::model()->getFields())
            ->joinLeft(array('pm'=>'post_meta'), 'p.id = pm.post_id', PostMetaTable::model()->formatFields('!post_id'))
            ->joinLeft(array('pe'=>'post_extra'), 'p.id = pe.post_id', PostExtraTable::model()->formatFields('!post_id'))
            ->where('p.id = ?', $post_id)
            ->fetchRow()
        ;
        
        //触发事件（可以定制一些box以扩展文章功能）
        \F::event()->trigger('admin_before_post_update', array(
            'cat_id'=>$post['cat_id'],
            'post_id'=>$post_id,
        ));
        
        $post['post_category'] = PostsCategoriesTable::model()->fetchCol('cat_id', array('post_id = ?'=>$post_id));
        $post['publish_time'] = date('Y-m-d H:i:s', $post['publish_time']);
        
        //文章对应标签
        $tags = $sql->from(array('pt'=>'posts_tags'), '')
            ->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', 'title')
            ->where('pt.post_id = '.$post_id)
            ->fetchAll();
        $tags_arr = array();
        foreach($tags as $t){
            $tags_arr[] = $t['title'];
        }
        $this->form()->setData(array('tags'=>implode(',', $tags_arr)));
        
        //分类树
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        
        //post files
        $this->view->files = PostsFilesTable::model()->fetchAll(array(
            'post_id = ?'=>$post_id,
        ), 'file_id,description,is_image', 'sort');

        $this->form()->setData($post, true);
        $this->view->post = $post;
        
        //附加属性
        $this->view->prop_set = PostPropService::service()->getPropSet($post['id']);
        
        $cat = CategoryService::service()->get($post['cat_id'], 'title');
        $this->layout->subtitle = '编辑文章- 所属分类：'.$cat['title'];
        if($this->checkPermission('cms/admin/post/create')){
            $this->layout->sublink = array(
                'uri'=>array('cms/admin/post/create', array(
                    'cat_id'=>$post['cat_id'],
                )),
                'text'=>'在此分类下发布文章',
            );
        }
        
        //box排序
        $_box_sort_settings = SettingService::service()->get('admin_post_box_sort');
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
        $post_id = $this->input->get('id', 'intval');
        
        PostService::service()->delete($post_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文章移入回收站', $post_id);
        
        Response::notify('success', array(
            'message'=>'一篇文章被移入回收站 - '.HtmlHelper::link('撤销', array('cms/admin/post/undelete', array(
                'id'=>$post_id,
            ))),
            'id'=>$post_id,
        ));
    }
    
    /**
     * 还原
     */
    public function undelete(){
        $post_id = $this->input->get('id', 'intval');
        
        if(!PostService::checkUndeletePermission($post_id)){
            throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
        }
        PostService::service()->undelete($post_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文章移出回收站', $post_id);
        
        Response::notify('success', array(
            'message'=>'一篇文章被还原',
            'id'=>$post_id,
        ));
    }
    
    public function remove(){
        $post_id = $this->input->get('id', 'intval');
        
        PostService::service()->remove($post_id);
        
        $this->actionlog(ActionlogsTable::TYPE_POST, '将文章永久删除', $post_id);
        
        Response::notify('success', array(
            'message'=>'一篇文章被永久删除',
            'id'=>$post_id,
        ));
    }
    
    /**
     * 文章排序
     */
    public function sort(){
        $post_id = $this->input->get('id', 'intval');
        PostsTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), array(
            'id = ?'=>$post_id,
        ));
        $this->actionlog(ActionlogsTable::TYPE_POST, '改变了文章排序', $post_id);
        
        $post = PostsTable::model()->find($post_id, 'sort');
        Response::notify('success', array(
            'message'=>'一篇文章的排序值被编辑',
            'data'=>array(
                'sort'=>$post['sort'],
            ),
        ));
    }
    
    /**
     * 单独渲染一个prop box
     */
    public function getPropBox(){
        $cat_id = $this->input->get('cat_id', 'intval');
        $post_id = $this->input->get('post_id', 'intval');
        
        //文章对应附加属性值
        $props = PostPropService::service()->getPropsByCatId($cat_id);
        if(!empty($props) && $post_id){
            $prop_set = PostPropService::service()->getPropSet($post_id, $props);
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
        $this->settingForm('admin_post_cat', '_setting_cat', array(
            'default_dep'=>2,
        ));
        
        $this->layout->subtitle = '文章分类';
        $this->view->cats = CategoryService::service()->getTree('_system_post');
        $root_node = CategoryService::service()->getByAlias('_system_post', 'id');
        $this->view->root = $root_node['id'];
        
        if($this->checkPermission('cms/admin/post/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加文章分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'文章',
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
                    if(!PostService::checkEditPermission($id, PostsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchPublish($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被发布');
                Response::notify('success', count($affected_rows) . '篇文章被发布');
            break;
            case 'set-draft':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, PostsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchDraft($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被标记为“草稿”');
                Response::notify('success', count($affected_rows) . '篇文章被标记为“草稿”');
            break;
            case 'set-pending':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, PostsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchPending($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被标记为“待审核”');
                Response::notify('success', count($affected_rows) . '篇文章被标记为“待审核”');
            break;
            case 'set-reviewed':
                foreach($ids as $id){
                    if(!PostService::checkEditPermission($id, PostsTable::STATUS_PUBLISHED)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchReviewed($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被标记为“通过审核”');
                Response::notify('success', count($affected_rows) . '篇文章被标记为“通过审核”');
            break;
            case 'delete':
                foreach($ids as $id){
                    if(!PostService::checkDeletePermission($id)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchDelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被移入回收站');
                Response::notify('success', count($affected_rows) . '篇文章被移入回收站');
            break;
            case 'undelete':
                foreach($ids as $id){
                    if(!PostService::checkUndeletePermission($id)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = PostService::service()->batchUndelete($ids);
                
                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被还原');
                Response::notify('success', count($affected_rows) . '篇文章被还原');
            break;
            case 'remove':
                foreach($ids as $id){
                    if(!PostService::checkRemovePermission($id)){
                        throw new HttpException('您无权限编辑该文章', 403, 'permission-denied');
                    }
                }
                
                $affected_rows = array();
                foreach($ids as $id){
                    if(PostService::service()->remove($id)){
                        $affected_rows[] = $id;
                    }
                }

                $this->actionlog(ActionlogsTable::TYPE_POST, '批处理：文章' . json_encode($affected_rows) . '被永久删除');
                Response::notify('success', count($affected_rows) . '篇文章被永久删除');
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
     * 验证文章别名是否存在（不排除已删除和未发布的文章）
     */
    public function isAliasNotExist(){
        if(PostsTable::model()->fetchRow(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            Response::json('', 0, '别名已存在');
        }else{
            Response::json();
        }
    }
    
    /**
     * 搜索
     */
    public function search(){
        if($cat_id = $this->input->request('cat_id', 'intval')){
            $cats = CategoryService::service()->getChildIds($cat_id);
            $cats[] = $cat_id;
        }
        $posts = PostsTable::model()->fetchAll(array(
            'title LIKE ?'=>'%'.$this->input->request('key', false).'%',
            'cat_id IN (?)'=>isset($cats) ? $cats : false,
        ), 'id,title', 'id DESC', 20);
        Response::json($posts);
    }
    
    /**
     * 返回各状态下的文章数
     */
    public function getCounts(){
        $data = array(
            'all'=>PostService::service()->getCount(),
            'published'=>PostService::service()->getCount(PostsTable::STATUS_PUBLISHED),
            'draft'=>PostService::service()->getCount(PostsTable::STATUS_DRAFT),
            'deleted'=>PostService::service()->getDeletedCount(),
        );
        
        if($this->post_review){
            $data['pending'] = PostService::service()->getCount(PostsTable::STATUS_PENDING);
            $data['reviewed'] = PostService::service()->getCount(PostsTable::STATUS_REVIEWED);
        }
        
        Response::json($data);
    }

    /**
     * 以json的方式返回文章分页列表，比index逻辑简单一些
     * @parameter string $keywords_field 当$keywords有值时，确定搜索字段
     * @parameter string $keywords
     * @parameter string $time_field 当$start_time或$end_time有值时，指定搜索字段
     * @parameter string $start_time
     * @parameter string $end_time
     * @parameter string $orderby
     * @parameter string $order
     * @parameter string $subclassification
     * @parameter string $cat_id
     * @parameter string $page_size
     */
    public function listAction(){
        //搜索条件验证，异常数据直接返回404
        $this->form('search')->setScene('final')->setRules(array(
            array('keywords_field', 'range', array(
                'range'=>PostsTable::model()->getFields(),
            )),
            array('time_field', 'range', array(
                'range'=>array('publish_time', 'create_time', 'update_time')
            )),
            array(array('start_time', 'end_time'), 'datetime'),
            array('orderby', 'range', array(
                'range'=>PostsTable::model()->getFields(),
            )),
            array('subclassification', 'range', array(
                'range'=>array('0', '1')
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('cat_id', 'exist', array('table'=>'categories', 'field'=>'id')),
            array('page_size', 'int', array('min'=>1)),
        ))->setFilters(array(
            'time_field'=>'trim',
            'start_time'=>'strtotime',
            'end_time'=>'strtotime',
            'orderby'=>'trim',
            'order'=>'trim',
            'cat_id'=>'intval',
            'subclassification'=>'intval',
            'keywords_field'=>'trim',
            'keywords'=>'trim',
        ))->check();
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,cat_id')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
        ;

        //分类搜索
        $cat_id = $this->form()->getData('cat_id');

        if($cat_id){
            if(!!$this->form()->getData('subclassification')){
                //包含子分类
                $limit_cat_children = CategoryService::service()->getChildIds($cat_id);
                $limit_cat_children[] = $cat_id;//加上父节点
                $sql->where(array('cat_id IN (?)'=>$limit_cat_children));
            }else{
                //不包含子分类
                $sql->where(array('cat_id = ?'=>$cat_id));
            }
        }

        //时间段
        if($this->form()->getData('start_time')){
            $sql->where(array("p.{$this->form()->getData('time_field')} > ?"=>$this->form()->getData('start_time')));
        }
        if($this->form()->getData('end_time')){
            $sql->where(array("p.{$this->form()->getData('time_field')} < ?"=>$this->form()->getData('end_time')));
        }

        //只返回可见文章
        $sql->where(PostsTable::getPublishedConditions('p'));
        
        $keywords = $this->form()->getData('keywords');
        if($keywords){
            $keywords_field = $this->form()->getData('keywords_field', 'title');
            if(in_array($keywords_field, array('title'))){
                $sql->where("p.{$keywords_field} LIKE ?", "%{$keywords}%");
            }else if(in_array($keywords_field, array('id', 'user_id'))){
                $sql->where("p.{$keywords_field} = ?", $keywords);
            }
        }

        //排序
        $orderBy = $this->form()->getData('orderby');
        if($orderBy){
            $order = $this->form()->getData('orderby', 'asc');
            $sql->order("P.{$orderBy} {$order}");
        }else{
            $sql->order('p.id DESC');
        }

        $listview = new ListView($sql, array(
            'page_size'=>$this->form()->getData('page_size', 10),
        ));

        Response::json(array(
            'posts'=>$listview->getData(),
            'pager'=>$listview->getPager(),
        ));
    }

    /**
     * 获取可用的文章分类
     */
    public function getCats(){
        $format = $this->input->request('format', 'trim', 'html');

        if($format == 'html'){
            //以html select标签的形式输出可用分类
            echo $this->form()->select('cat_id', HtmlHelper::getSelectOptions(
                CategoryService::service()->getTree('_system_post')
            ), array(
                'class'=>'form-control'
            ));
        }else if($format == 'tree'){
            Response::json(array(
                'cats'=>CategoryService::service()->getTree('_system_post'),
            ));
        }else if($format == 'list'){
            Response::json(array(
                'cats'=>CategoryService::service()->getChildren('_system_post'),
            ));
        }
    }

    /**
     * 后台预览一篇文章（非前端主题式样预览）
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
                'id'=>'文章ID',
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
            throw new HttpException("指定文章ID[{$this->form()->getData('id')}]不存在");
        }
        
        $this->view->renderPartial(null, array(
            'post'=>$post
        ));
    }
}