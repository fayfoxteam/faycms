<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use apidoc\models\tables\Outputs;
use apidoc\models\tables\Models;
use fay\core\Response;
use fay\models\Setting;
use fay\helpers\StringHelper;

/**
 * 数据模型
 */
class ModelController extends AdminController{
	/**
	 * box列表
	 */
	public $boxes = array(
		array('name'=>'sample', 'title'=>'示例值'),
		array('name'=>'since', 'title'=>'自从'),
		array('name'=>'props', 'title'=>'属性'),
	);
	
	/**
	 * 默认box排序
	*/
	public $default_box_sort = array(
		'side'=>array(
			'since'
		),
		'normal'=>array(
			'sample', 'props'
		),
	);
	
	public function index(){
		$this->layout->subtitle = '数据模型';
		
		if($this->checkPermission('admin/model/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/model/create'),
				'text'=>'新增数据模型',
			);
		}
		
		$sql = new Sql();
		$sql->from('apidoc_models')
			->where('id >= 1000');

		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		
		$this->form()->setModel(Outputs::model());
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '新增数据模型';
		if($this->checkPermission('admin/model/index')){
			$this->layout->sublink = array(
				'uri'=>array('admin/model/index'),
				'text'=>'数据模型列表',
			);
		}
		
		$this->form()->setModel(Models::model());
		
		//启用的编辑框
		$_setting_key = 'admin_model_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = Models::model()->fillData($this->input->post(), true, 'insert');
			$data['create_time'] = $this->current_time;
			$data['last_modified_time'] = $this->current_time;
			$data['user_id'] = $this->current_user;
			$model_id = Models::model()->insert($data);
			
			
			
			Response::notify('success', '数据模型添加成功', array('admin/model/edit', array(
				'id'=>$model_id,
			)));
		}
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_model_box_sort');
		$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
		$this->view->_box_sort_settings = $_box_sort_settings;
		
		$this->layout->_setting_panel = '_setting_edit';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array();
		$this->form('setting')
			->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
				'enabled_boxes'=>$enabled_boxes,
			));
		
		//所有数据模型
		$models = Models::model()->fetchAll(array(), 'id,name,description');
		$modelMap = array();
		foreach($models as $m){
			$modelMap[$m['id']] = $m['name'] . '(' . StringHelper::niceShort($m['description'], 10) . ')';
		}
		$this->view->models = $modelMap;
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑数据模型';
		if($this->checkPermission('admin/model/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/model/create'),
				'text'=>'新增数据模型',
			);
		}
		
		$model_id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(Models::model());
		
		//启用的编辑框
		$_setting_key = 'admin_model_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = Models::model()->fillData($this->input->post(), true, 'update');
			$data['last_modified_time'] = $this->current_time;
			Models::model()->update($data, $model_id);
			
			
			
			Response::notify('success', '数据模型添加成功', array('admin/model/edit', array(
				'id'=>$model_id,
			)));
		}
		
		$model = Models::model()->find($model_id);
		$this->form()->setData($model);
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_model_box_sort');
		$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
		$this->view->_box_sort_settings = $_box_sort_settings;
		
		$this->layout->_setting_panel = '_setting_edit';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array();
		$this->form('setting')
			->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
				'enabled_boxes'=>$enabled_boxes,
			));
		
		//所有数据模型
		$models = Models::model()->fetchAll(array(), 'id,name,description');
		$modelMap = array();
		foreach($models as $m){
			$modelMap[$m['id']] = $m['name'] . '(' . StringHelper::niceShort($m['description'], 10) . ')';
		}
		$this->view->models = $modelMap;
		
		$this->view->render();
	}
	
	public function remove(){
		
	}
	
	public function search(){
		$keywords = $this->input->request('key', 'trim');
		
		$sql = new Sql();
		$models = $sql->from(array('m'=>Models::model()->getName()), array('id', 'name', 'description'))
			->orWhere(array(
				'name LIKE ?'=>"%{$keywords}%",
				'description LIKE ?'=>"%{$keywords}%",
			))
			->fetchAll();
		
		$modelMap = array();
		foreach($models as $m){
			$modelMap[] = array(
				'id'=>$m['id'],
				'name'=>$m['name'],
				'title'=>$m['name'] . '(' . StringHelper::niceShort(strip_tags($m['description']), 10) . ')',
			);
		}
		
		Response::json($modelMap);
	}
}