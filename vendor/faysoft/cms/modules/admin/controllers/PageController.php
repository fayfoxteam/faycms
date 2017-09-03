<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\CategoriesTable;
use cms\models\tables\PagesCategoriesTable;
use cms\models\tables\PagesTable;
use cms\services\CategoryService;
use cms\services\PageService;
use cms\services\SettingService;
use fay\common\ListView;
use fay\exceptions\NotFoundHttpException;
use fay\core\Response;
use fay\core\Sql;
use fay\helpers\HtmlHelper;

class PageController extends AdminController{
    public $boxes = array(
        array('name'=>'alias', 'title'=>'别名'),
        array('name'=>'views', 'title'=>'阅读数'),
        array('name'=>'category', 'title'=>'分类'),
        array('name'=>'thumbnail', 'title'=>'缩略图'),
        array('name'=>'seo', 'title'=>'SEO优化'),
        array('name'=>'abstract', 'title'=>'摘要'),
    );
    
    public $default_box_sort = array(
        'side'=>array(
            'category', 'alias', 'views', 'thumbnail',
        ),
        'normal'=>array(
            'abstract', 'seo',
        ),
    );

    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'page';
    }
    
    public function create(){
        $this->layout->subtitle = '添加页面';
        $this->view->cats = CategoryService::service()->getTree('_system_page');
        
        $this->form()->setModel(PagesTable::model())
            ->setData($this->input->request());
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            $data['create_time'] = $this->current_time;
            $data['update_time'] = $this->current_time;
            $data['author'] = $this->current_user;
            $page_id = PagesTable::model()->insert($data);
            
            $page_category = $this->form()->getData('page_category');
            if(!empty($page_category)){
                foreach($page_category as $page_cat){
                    PagesCategoriesTable::model()->insert(array(
                        'page_id'=>$page_id,
                        'cat_id'=>$page_cat,
                    ));
                }
            }
            
            $this->actionlog(ActionlogsTable::TYPE_PAGE, '添加页面', $page_id);
            return Response::notify(Response::NOTIFY_SUCCESS, '页面发布成功', array('cms/admin/page/edit', array(
                'id'=>$page_id,
            )));
        }
        
        $cat_id = $this->input->get('cat_id', 'intval');
        $this->form()->setData(array(
            'cat_id'=>$cat_id,
        ));
        
        $_settings = SettingService::service()->get('admin_page_box_sort');
        $_settings || $_settings = $this->default_box_sort;
        $this->view->_settings = $_settings;
        
        //页面设置
        $_setting_key = 'admin_page_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_boxes', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        return $this->view->render();
    }
    
    public function index(){
        //搜索条件验证，异常数据直接返回404
        $this->form()->setScene('final')->setRules(array(
            array('keyword_field', 'range', array(
                'range'=>PagesTable::model()->getFields(),
            )),
            array('status', 'range', array(
                'range'=>array(PagesTable::STATUS_PUBLISHED, PagesTable::STATUS_DRAFT),
            )),
            array('deleted', 'range', array(
                'range'=>array(0, 1),
            )),
            array('time_field', 'range', array(
                'range'=>array('create_time', 'update_time')
            )),
            array(array('start_time', 'end_time'), 'datetime'),
            array('orderby', 'range', array(
                'range'=>PagesTable::model()->getFields(),
            )),
            array('order', 'range', array(
                'range'=>array('asc', 'desc'),
            )),
            array('cat_id', 'int', array('min'=>1))
        ))->check();
        
        $this->layout->subtitle = '所有页面';
        
        //页面设置
        $this->settingForm('admin_page_index', '_setting_index', array(
            'cols'=>array('category', 'status', 'alias', 'update_time', 'create_time', 'sort'),
            'page_size'=>10,
        ));
        
        $cat_id = $this->input->get('cat_id', 'intval');
        if($cat_id){
            $sub_link_params = array(
                'page_category'=>$cat_id,
            );
        }else{
            $sub_link_params = array();
        }
        
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/page/create', $sub_link_params),
            'text'=>'添加页面',
        );
        
        $sql = new Sql();
        $sql->from(array('p'=>'pages'), PagesTable::model()->formatFields('!content'));
        
        if($this->input->get('deleted', 'intval') == 1){
            $sql->where('p.delete_time > 0');
        }else if($this->input->get('status', 'intval') !== null && $this->input->get('deleted', 'intval') != 1){
            $sql->where(array(
                'p.status = ?'=>$this->input->get('status', 'intval'),
                'p.delete_time = 0',
            ));
        }else{
            $sql->where('p.delete_time = 0');
        }
        
        if($this->input->get('keywords')){
            $sql->where(array(
                "p.{$this->input->get('keyword_field')} LIKE ?"=>'%'.$this->input->get('keywords').'%',
            ));
        }
        if($this->input->get('start_time')){
            $sql->where(array(
                "p.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time','strtotime'),
            ));
        }
        if($this->input->get('end_time')){
            $sql->where(array(
                "p.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time','strtotime'),
            ));
        }
        if($cat_id){
            $sql->joinLeft(array('pc'=>'pages_categories'), 'p.id = pc.page_id')
                ->where(array(
                    'pc.cat_id = ?'=>$cat_id,
                ))
                ->distinct(true);
        }
        
        if($this->input->get('orderby')){
            $this->view->orderby = $this->input->get('orderby');
            $this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
            $sql->order("p.{$this->view->orderby} {$this->view->order}");
        }else{
            $sql->order('p.id DESC');
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 10,
            'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
        ));
        
        //所有分类
        $this->view->cats = CategoryService::service()->getTree('_system_page');
        
        return $this->view->render();
    }
    
    public function edit(){
        $this->layout->subtitle = '编辑页面';
        $this->layout->sublink = array(
            'uri'=>array('cms/admin/page/create'),
            'text'=>'添加页面',
        );
        
        $_settings = SettingService::service()->get('admin_page_box_sort');
        $_settings || $_settings = $this->default_box_sort;
        $this->view->_settings = $_settings;
        
        //页面设置
        $_setting_key = 'admin_page_boxes';
        $enabled_boxes = $this->getEnabledBoxes($_setting_key);
        $this->settingForm($_setting_key, '_setting_boxes', array(), array(
            'enabled_boxes'=>$enabled_boxes,
        ));
        
        $page_id = intval($this->input->get('id', 'intval'));
        
        $this->view->cats = CategoryService::service()->getTree('_system_page');
        
        $this->form()->setModel(PagesTable::model());
        
        if($this->input->post() && $this->form()->check()){
            $data = $this->form()->getFilteredData();
            $data['update_time'] = $this->current_time;
            PagesTable::model()->update($data, $page_id);
            if(in_array('category', $enabled_boxes)){
                PagesCategoriesTable::model()->delete("page_id = {$page_id}");
                
                $page_category = $this->form()->getData('page_category');
                if(!empty($page_category)){
                    foreach($page_category as $page_cat){
                        PagesCategoriesTable::model()->insert(array(
                            'page_id'=>$page_id,
                            'cat_id'=>$page_cat,
                        ));
                    }
                }
            }
            
            $this->actionlog(ActionlogsTable::TYPE_PAGE, '编辑页面', $page_id);
            return Response::notify(Response::NOTIFY_SUCCESS, '一个页面被编辑');
        }
        if($page = PagesTable::model()->find($page_id)){
            $page['page_category'] = PageService::service()->getPageCatIds($page_id);
            $this->view->page = $page;
            $this->form()->setData($page);
        }else{
            throw new NotFoundHttpException('无效的页面ID');
        }

        return $this->view->render();
    }
    
    public function delete(){
        $page_id = $this->input->get('id', 'intval');
        PagesTable::model()->update(array('delete_time'=>$this->current_time), $page_id);
        
        return Response::notify(Response::NOTIFY_SUCCESS, array(
            'id'=>$page_id,
            'message'=>'一个页面被移入回收站 - '.HtmlHelper::link('撤销', array('cms/admin/page/undelete', array(
                'id'=>$page_id,
            ))),
        ));
    }
    
    public function undelete(){
        $page_id = $this->input->get('id', 'intval');
        PagesTable::model()->update(array('delete_time'=>0), $page_id);
        $this->actionlog(ActionlogsTable::TYPE_PAGE, '将页面移出回收站', $page_id);
        
        return Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个页面被移出回收站',
        ));
    }
    
    public function remove(){
        PagesTable::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
        PagesCategoriesTable::model()->delete(array('page_id = ?'=>$this->input->get('id', 'intval')));
        $this->actionlog(ActionlogsTable::TYPE_PAGE, '将页面永久删除', $this->input->get('id', 'intval'));
        
        return Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个页面被永久删除',
        ));
    }
    
    public function sort(){
        $page_id = $this->input->get('id', 'intval');
        PagesTable::model()->update(array(
            'sort'=>$this->input->get('sort', 'intval'),
        ), $page_id);
        $this->actionlog(ActionlogsTable::TYPE_PAGE, '改变了页面排序', $page_id);
        
        $page = PagesTable::model()->find($page_id, 'sort');
        return Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'一个页面的排序值被编辑',
            'data'=>array(
                'sort'=>$page['sort'],
            ),
        ));
    }

    /**
     * 分类管理
     */
    public function cat(){
        $this->layout->current_directory = 'page';
    
        $this->layout->subtitle = '页面分类';
        $this->view->cats = CategoryService::service()->getTree('_system_page');
        $root_node = CategoryService::service()->get('_system_page', 'id');
        $this->view->root = $root_node['id'];

        \F::form('create')->setModel(CategoriesTable::model());
        \F::form('edit')->setModel(CategoriesTable::model());
    
        if($this->checkPermission('cms/admin/page/cat-create')){
            $this->layout->sublink = array(
                'uri'=>'#create-cat-dialog',
                'text'=>'添加页面根分类',
                'html_options'=>array(
                    'class'=>'create-cat-link',
                    'data-title'=>'页面',
                    'data-id'=>$root_node['id'],
                ),
            );
        }
        return $this->view->render();
    }
    
    public function isAliasNotExist(){
        if(PagesTable::model()->has(array(
            'alias = ?'=>$this->input->request('alias', 'trim'),
            'id != ?'=>$this->input->request('id', 'intval', false),
        ))){
            return Response::json('', 0, '别名已存在');
        }else{
            return Response::json();
        }
    }
    
    public function search(){
        $pages = PagesTable::model()->fetchAll(array(
            'title LIKE ?'=>'%'.$this->input->request('key', false).'%'
        ), 'id,title', 'id DESC', 20);
        return Response::json($pages);
    }
}