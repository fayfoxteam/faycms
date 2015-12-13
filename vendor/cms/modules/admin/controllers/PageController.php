<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Category;
use fay\models\tables\Pages;
use fay\models\tables\PagesCategories;
use fay\models\tables\Actionlogs;
use fay\models\Setting;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\Page;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\models\Flash;

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
		$this->view->cats = Category::model()->getTree('_system_page');
		
		$this->form()->setModel(Pages::model())
			->setData($this->input->request());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				$data['create_time'] = $this->current_time;
				$data['last_modified_time'] = $this->current_time;
				$data['author'] = $this->current_user;
				$page_id = Pages::model()->insert($data);
				
				$page_category = $this->form()->getData('page_category');
				if(!empty($page_category)){
					foreach($page_category as $page_cat){
						PagesCategories::model()->insert(array(
							'page_id'=>$page_id,
							'cat_id'=>$page_cat,
						));
					}
				}
				
				$this->actionlog(Actionlogs::TYPE_PAGE, '添加页面', $page_id);
				Response::notify('success', '页面发布成功');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$cat_id = $this->input->get('cat_id', 'intval');
		$this->form()->setData(array(
			'cat_id'=>$cat_id,
		));
		
		$_settings = Setting::model()->get('admin_page_box_sort');
		$_settings || $_settings = $this->default_box_sort;
		$this->view->_settings = $_settings;
		
		$this->layout->_setting_panel = '_setting_boxes';
		$_setting_key = 'admin_page_boxes';
		$this->form('setting')
			->setModel(Setting::model())
			->setJsModel('setting')
			->setData(array(
				'_key'=>$_setting_key,
				'enabled_boxes'=>$this->getEnabledBoxes($_setting_key),
			));
		
		$this->view->render();
	}
	
	public function index(){
		$this->layout->subtitle = '所有页面';
		$this->layout->_setting_panel = '_setting_index';
		$this->view->_settings = Setting::model()->get('admin_page_index');
		$this->view->_settings === null && $this->view->_settings = array(
			'cols'=>array('category', 'status', 'alias', 'last_modified_time', 'create_time', 'sort'),
			'page_size'=>10,
		);
		
		$cat_id = $this->input->get('cat_id', 'intval');
		if($cat_id){
			$sub_link_params = array(
				'page_category'=>$cat_id,
			);
		}else{
			$sub_link_params = array();
		}
		
		$this->layout->sublink = array(
			'uri'=>array('admin/page/create', $sub_link_params),
			'text'=>'添加页面',
		);
		
		$sql = new Sql();
		$sql->from('pages', 'p', '!content');
		
		if($this->input->get('deleted', 'intval') == 1){
			$sql->where('p.deleted = 1');
		}else if($this->input->get('status', 'intval') !== null && $this->input->get('delete', 'intval') != 1){
			$sql->where(array(
				'p.status = ?'=>$this->input->get('status', 'intval'),
				'p.deleted <> 1',
			));
		}else{
			$sql->where('p.deleted = 0');
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
			$sql->joinLeft('pages_categories', 'pc', 'p.id = pc.page_id')
				->where(array(
					'pc.cat_id = ?'=>$cat_id,
				))
				->distinct(true);
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("p.{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('p.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 10,
			'empty_text'=>'<tr><td colspan="'.(count($this->view->_settings['cols']) + 2).'" align="center">无相关记录！</td></tr>',
		));
		
		//所有分类
		$this->view->cats = Category::model()->getTree('_system_page');
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑页面';
		$this->layout->sublink = array(
			'uri'=>array('admin/page/create'),
			'text'=>'添加页面',
		);
		
		$_settings = Setting::model()->get('admin_page_box_sort');
		$_settings || $_settings = $this->default_box_sort;
		$this->view->_settings = $_settings;
		
		$this->layout->_setting_panel = '_setting_boxes';
		$_setting_key = 'admin_page_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		$this->form('setting')
			->setModel(Setting::model())
			->setJsModel('setting')
			->setData(array(
				'_key'=>$_setting_key,
				'enabled_boxes'=>$enabled_boxes,
			));
		
		$page_id = intval($this->input->get('id', 'intval'));
		
		$this->view->cats = Category::model()->getTree('_system_page');
		
		$this->form()->setModel(Pages::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				$data['last_modified_time'] = $this->current_time;
				$result = Pages::model()->update($data, $page_id);
				if(in_array('category', $enabled_boxes)){
					PagesCategories::model()->delete("page_id = {$page_id}");
					
					$page_category = $this->form()->getData('page_category');
					if(!empty($page_category)){
						foreach($page_category as $page_cat){
							PagesCategories::model()->insert(array(
								'page_id'=>$page_id,
								'cat_id'=>$page_cat,
							));
						}
					}
				}
				
				$this->actionlog(Actionlogs::TYPE_PAGE, '编辑页面', $page_id);
				Flash::set('一个页面被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		if($page = Pages::model()->find($page_id)){
			$page['page_category'] = Page::model()->getPageCatIds($page_id);
			$this->view->page = $page;
			$this->form()->setData($page);
		}else{
			throw new HttpException('无效的页面ID');
		}

		$this->view->render();
	}
	
	public function delete(){
		$page_id = $this->input->get('id', 'intval');
		Pages::model()->update(array('deleted'=>1), $page_id);
		
		Response::notify('success', array(
			'id'=>$page_id,
			'message'=>'一个页面被移入回收站 - '.Html::link('撤销', array('admin/page/undelete', array(
				'id'=>$page_id,
			))),
		));
	}
	
	public function undelete(){
		$page_id = $this->input->get('id', 'intval');
		Pages::model()->update(array('deleted'=>0), $page_id);
		$this->actionlog(Actionlogs::TYPE_PAGE, '将页面移出回收站', $page_id);
		
		Response::notify('success', array(
			'message'=>'一个页面被移出回收站',
		));
	}
	
	public function remove(){
		Pages::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		PagesCategories::model()->delete(array('page_id = ?'=>$this->input->get('id', 'intval')));
		$this->actionlog(Actionlogs::TYPE_PAGE, '将页面永久删除', $this->input->get('id', 'intval'));
		
		Response::notify('success', array(
			'message'=>'一个页面被永久删除',
		));
	}
	
	public function sort(){
		$page_id = $this->input->get('id', 'intval');
		$result = Pages::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), $page_id);
		$this->actionlog(Actionlogs::TYPE_PAGE, '改变了页面排序', $page_id);
		
		$page = Pages::model()->find($page_id, 'sort');
		Response::notify('success', array(
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
		$this->view->cats = Category::model()->getTree('_system_page');
		$root_node = Category::model()->getByAlias('_system_page', 'id');
		$this->view->root = $root_node['id'];
	
		$root_cat = Category::model()->getByAlias('_system_page', 'id');
		if($this->checkPermission('admin/page/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加页面根分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'页面',
					'data-id'=>$root_cat['id'],
				),
			);
		}
		$this->view->render();
	}
	
	public function isAliasNotExist(){
		$alias = $this->input->request('value', 'trim');
		if(Pages::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '别名已存在');
		}else{
			Response::json();
		}
	}
	
	public function search(){
		$pages = Pages::model()->fetchAll(array(
			'title LIKE ?'=>'%'.$this->input->request('key', false).'%'
		), 'id,title', 'id DESC', 20);
		Response::json($pages);
	}
}