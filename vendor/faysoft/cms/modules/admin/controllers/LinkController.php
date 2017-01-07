<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\LinksTable;
use fay\models\tables\ActionlogsTable;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\services\CategoryService;

class LinkController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'link';
	}
	
	public function create(){
		$this->layout->subtitle = '添加链接';
		
		$this->form()->setModel(LinksTable::model());
		if($this->input->post() && $this->form()->check()){
			$data = $this->form()->getFilteredData();
			isset($data['visible']) || $data['visible'] = 1;
			$data['create_time'] = $this->current_time;
			$data['user_id'] = $this->current_user;
			$data['last_modified_time'] = $this->current_time;
			$link_id = LinksTable::model()->insert($data);
			$this->actionlog(ActionlogsTable::TYPE_LINK, '添加友情链接', $link_id);
			Response::notify('success', '链接添加成功', array('admin/link/edit', array('id'=>$link_id)));
		}
		
		$this->view->cats = CategoryService::service()->getTree('_system_link');
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑链接';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/link/create'),
			'text'=>'添加链接',
		);
		
		$this->form()->setModel(LinksTable::model());
		$id = $this->input->get('id', 'intval');
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				isset($data['visible']) || $data['visible'] = 1;
				$data['visible'] = $this->input->post('visible', 'intval', 1);
				$data['last_modified_time'] = $this->current_time;
				LinksTable::model()->update($data, array('id = ?'=>$id));
				$this->actionlog(ActionlogsTable::TYPE_LINK, '编辑友情链接', $id);
				Response::notify('success', '一个链接被编辑', false);
			}
		}
		if($link = LinksTable::model()->find($id)){
			$this->form()->setData($link);
			
			$this->layout->sublink = array(
				'uri'=>array('admin/link/create', array(
					'cat_id'=>$link['cat_id'],
				)),
				'text'=>'同分类下新增链接',
			);
			
			$this->view->cats = CategoryService::service()->getTree('_system_link');
			$this->view->render();
		}
	}
	
	public function index(){
		//搜索条件验证，异常数据直接返回404
		$this->form('search')->setScene('final')->setRules(array(
			array('orderby', 'range', array(
				'range'=>LinksTable::model()->getFields(),
			)),
			array('order', 'range', array(
				'range'=>array('asc', 'desc'),
			)),
			array('time_field', 'range', array(
				'range'=>array('publish_time', 'create_time', 'last_modified_time')
			)),
		))->check();
		
		$this->layout->subtitle = '链接';
		
		$cat_id = $this->input->get('cat_id', 'intval');
		if($cat_id){
			$sub_link_params = array(
				'cat_id'=>$cat_id,
			);
		}else{
			$sub_link_params = array();
		}
		
		$this->layout->sublink = array(
			'uri'=>array('admin/link/create', $sub_link_params),
			'text'=>'添加链接',
		);
		
		$sql = new Sql();
		$sql->from(array('l'=>'links'))
			->joinLeft(array('c'=>'categories'), 'l.cat_id = c.id', 'title AS cat_title');
		
		if($this->input->get('title')){
			$sql->where(array('l.title LIKE ?'=>'%'.$this->input->get('title', 'trim').'%'));
		}
		
		if($cat_id){
			$sql->where(array('l.cat_id = ?'=>$cat_id));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$listview = new ListView($sql, array(
			'empty_text'=>'<tr><td colspan="6" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
		$this->view->cats = CategoryService::service()->getTree('_system_link');
		
		$this->view->render();
	}
	
	public function remove(){
		LinksTable::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		
		$this->actionlog(ActionlogsTable::TYPE_LINK, '移除友情链接', $this->input->get('id', 'intval'));
		
		Response::notify('success', '一个友情链接被永久删除', array('admin/link/index', $this->input->get()));
	}
	
	public function sort(){
		$id = $this->input->get('id', 'intval');
		LinksTable::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(ActionlogsTable::TYPE_LINK, '改变了友情链接排序', $id);
		
		$link = LinksTable::model()->find($id, 'sort');
		Response::notify('success', array(
			'message'=>'改变了友情链接排序值',
			'data'=>array(
				'sort'=>$link['sort'],
			),
		));
	}
	
	/**
	 * 分类管理
	 */
	public function cat(){
		$this->layout->current_directory = 'link';
	
		$this->layout->subtitle = '友情链接分类';
		$this->view->cats = CategoryService::service()->getTree('_system_link');
		$root_node = CategoryService::service()->getByAlias('_system_link', 'id');
		$this->view->root = $root_node['id'];
		
		if($this->checkPermission('admin/link/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加友情链接分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'友情链接',
					'data-id'=>$root_node['id'],
				),
			);
		}
		
		$this->view->render();
	}
}