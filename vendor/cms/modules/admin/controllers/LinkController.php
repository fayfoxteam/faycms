<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Links;
use fay\models\tables\Actionlogs;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\models\Category;

class LinkController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'link';
	}
	
	public function create(){
		$this->layout->subtitle = '添加链接';
		
		$this->form()->setModel(Links::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				isset($data['visiable']) || $data['visiable'] = 1;
				$data['create_time'] = $this->current_time;
				$data['user_id'] = $this->current_user;
				$data['last_modified_time'] = $this->current_time;
				$link_id = Links::model()->insert($data);
				$this->actionlog(Actionlogs::TYPE_LINK, '添加友情链接', $link_id);
				Response::output('success', '链接添加成功', array('admin/link/edit', array('id'=>$link_id)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->cats = Category::model()->getTree('_system_link', 'id,title');
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑链接';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/link/create'),
			'text'=>'添加链接',
		);
		
		$this->form()->setModel(Links::model());
		$id = $this->input->get('id', 'intval');
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				isset($data['visiable']) || $data['visiable'] = 1;
				$data['visiable'] = $this->input->post('visiable', 'intval', 1);
				$data['last_modified_time'] = $this->current_time;
				Links::model()->update($data, array('id = ?'=>$id));
				$this->actionlog(Actionlogs::TYPE_LINK, '编辑友情链接', $id);
				$this->flash->set('一个链接被编辑', 'success');
			}
		}
		if($link = Links::model()->find($id)){
			$this->form()->setData($link);
			
			$this->layout->sublink = array(
				'uri'=>array('admin/link/create', array(
					'cat_id'=>$link['cat_id'],
				)),
				'text'=>'同分类下新增链接',
			);
			
			$this->view->cats = Category::model()->getTree('_system_link', 'id,title');
			$this->view->render();
		}
	}
	
	public function index(){
		$this->layout->subtitle = '链接';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/link/create'),
			'text'=>'添加链接',
		);
		
		$sql = new Sql();
		$sql->from('links');
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$listview = new ListView($sql, array(
			'emptyText'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
		$this->view->render();
	}
	
	public function remove(){
		Links::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		
		$this->actionlog(Actionlogs::TYPE_LINK, '移除友情链接', $this->input->get('id', 'intval'));
		
		Response::output('success', '一个友情链接被永久删除', array('admin/link/index', $this->input->get()));
	}
	
	public function sort(){
		$id = $this->input->get('id', 'intval');
		$result = Links::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_LINK, '改变了友情链接排序', $id);
		
		$link = Links::model()->find($id, 'sort');
		Response::output('success', array(
			'message'=>'改变了友情链接排序值',
			'sort'=>$link['sort'],
		));
	}
	
	/**
	 * 分类管理
	 */
	public function cat(){
		$this->layout->current_directory = 'link';
	
		$this->layout->subtitle = '友情链接分类';
		$this->view->cats = Category::model()->getTree('_system_link');
		$root_node = Category::model()->getByAlias('_system_link', 'id');
		$this->view->root = $root_node['id'];
		
		$root_cat = Category::model()->getByAlias('_system_link', 'id');
		if($this->checkPermission('admin/link/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加友情链接分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'友情链接',
					'data-id'=>$root_cat['id'],
				),
			);
		}
		
		$this->view->render();
	}
}