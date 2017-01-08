<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use apidoc\models\tables\OutputsTable;
use apidoc\models\tables\ModelsTable;
use fay\core\Response;
use fay\services\SettingService;
use fay\helpers\StringHelper;
use apidoc\models\tables\ModelPropsTable;
use fay\core\ErrorException;

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
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'api';
	}
	
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
		
		$this->form()->setModel(OutputsTable::model());
		
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
		
		$this->form()->setModel(ModelsTable::model());
		
		//启用的编辑框
		$_setting_key = 'admin_model_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = ModelsTable::model()->fillData($this->input->post(), true, 'insert');
			$data['create_time'] = $this->current_time;
			$data['last_modified_time'] = $this->current_time;
			$data['user_id'] = $this->current_user;
			$model_id = ModelsTable::model()->insert($data);
			
			$props = $this->input->post('props');
			$i = 0;
			foreach($props as $p){
				$i++;
				$type_model = ModelsTable::model()->fetchRow(array(
					'name = ?'=>$p['type_name'],
				), 'id');
				if(!$type_model){
					throw new ErrorException('指定属性类型不存在', $p['type_name']);
				}
				
				$prop = ModelPropsTable::model()->fillData($p, true, 'insert');
				$prop['create_time'] = $this->current_time;
				$prop['last_modified_time'] = $this->current_time;
				$prop['model_id'] = $model_id;
				$prop['type'] = $type_model['id'];
				$prop['sort'] = $i;
				ModelPropsTable::model()->insert($prop);
			}
			
			Response::notify('success', '数据模型添加成功', array('admin/model/edit', array(
				'id'=>$model_id,
			)));
		}
		
		//可配置信息
		$_box_sort_settings = SettingService::service()->get('admin_model_box_sort');
		$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
		$this->view->_box_sort_settings = $_box_sort_settings;
		
		//页面设置
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		$this->settingForm($_setting_key, '_setting_edit', array(), array(
			'enabled_boxes'=>$enabled_boxes,
		));
		
		//所有数据模型
		$models = ModelsTable::model()->fetchAll(array(), 'id,name,description');
		$modelMap = array();
		foreach($models as $m){
			$modelMap[$m['id']] = $m['name'] . '(' . StringHelper::niceShort($m['description'], 10) . ')';
		}
		$this->view->models = $modelMap;
		
		//属性表单规则
		$this->form('prop')->setModel(ModelPropsTable::model())
			->setRule(array('type_name', 'required'))
			->setRule(array('type_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
			->setRule(array('type_name', 'ajax', array('url'=>array('admin/model/is-name-exist'))))
			->setLabels(array(
				'model'=>'类型',
			));
		
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
		
		$this->form()->setModel(ModelsTable::model());
		
		//启用的编辑框
		$_setting_key = 'admin_model_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = ModelsTable::model()->fillData($this->input->post(), true, 'update');
			$data['last_modified_time'] = $this->current_time;
			ModelsTable::model()->update($data, $model_id);
			
			$props = $this->input->post('props');
			//删除已被删除的属性
			if($props){
				ModelPropsTable::model()->delete(array(
					'model_id = ?'=>$model_id,
					'id NOT IN (?)'=>array_keys($props),
				));
			}else if(in_array('props', $enabled_boxes)){
				ModelPropsTable::model()->delete(array(
					'model_id = ?'=>$model_id,
				));
			}
			//获取已存在的属性
			$old_prop_ids = ModelPropsTable::model()->fetchCol('id', array(
				'model_id = ?'=>$model_id,
			));
			
			$i = 0;
			foreach($props as $prop_id => $p){
				$i++;
				$type_model = ModelsTable::model()->fetchRow(array(
					'name = ?'=>$p['type_name'],
				), 'id');
				if(!$type_model){
					throw new ErrorException('指定属性类型不存在', $p['type_name']);
				}
				
				if(in_array($prop_id, $old_prop_ids)){
					$prop = ModelPropsTable::model()->fillData($p, true, 'update');
					$prop['sort'] = $i;
					$prop['type'] = $type_model['id'];
					$prop['last_modified_time'] = $this->current_time;
					ModelPropsTable::model()->update($prop, $prop_id);
				}else{
					$prop = ModelPropsTable::model()->fillData($p, true, 'insert');
					$prop['model_id'] = $model_id;
					$prop['sort'] = $i;
					$prop['type'] = $type_model['id'];
					$prop['create_time'] = $this->current_time;
					$prop['last_modified_time'] = $this->current_time;
					ModelPropsTable::model()->insert($prop);
				}
			}
			
			Response::notify('success', '数据模型编辑成功', array('admin/model/edit', array(
				'id'=>$model_id,
			)));
		}
		
		$model = ModelsTable::model()->find($model_id);
		$this->form()->setData($model);
		
		//可配置信息
		$_box_sort_settings = SettingService::service()->get('admin_model_box_sort');
		$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
		$this->view->_box_sort_settings = $_box_sort_settings;
		
		//页面设置
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		$this->settingForm($_setting_key, '_setting_edit', array(), array(
			'enabled_boxes'=>$enabled_boxes,
		));
		
		//所有数据模型
		$models = ModelsTable::model()->fetchAll(array(), 'id,name,description');
		$modelMap = array();
		foreach($models as $m){
			$modelMap[$m['id']] = $m['name'] . '(' . StringHelper::niceShort($m['description'], 10) . ')';
		}
		$this->view->models = $modelMap;
		
		//属性表单规则
		$this->form('prop')->setModel(ModelPropsTable::model())
			->setRule(array('type_name', 'required'))
			->setRule(array('type_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
			->setRule(array('type_name', 'ajax', array('url'=>array('admin/model/is-name-exist'))))
			->setLabels(array(
				'type_name'=>'类型',
			));
			
		//原属性
		$sql = new Sql();
		$this->view->props = $sql->from(array('mp'=>ModelPropsTable::model()->getTableName()))
			->joinLeft(array('m'=>ModelsTable::model()->getTableName()), 'mp.type = m.id', 'name AS type_name')
			->where('mp.model_id = ?', $model_id)
			->order('mp.sort')
			->fetchAll();
		
		$this->view->render();
	}
	
	public function remove(){
		
	}
	
	public function search(){
		$keywords = $this->input->request('key', 'trim');
		
		$sql = new Sql();
		$models = $sql->from(array('m'=>ModelsTable::model()->getTableName()), array('id', 'name', 'description'))
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
	
	public function isNameExist(){
		if(ModelsTable::model()->fetchRow(array(
			'name = ?'=>$this->input->request('name', 'trim'),
		))){
			echo Response::json();
		}else{
			echo Response::json('', 0, '模型不存在');
		}
	}
}