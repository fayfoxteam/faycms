<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\Setting;
use fay\helpers\ArrayHelper;
use fay\models\Category;
use apidoc\models\tables\Apis;
use apidoc\models\tables\Inputs;
use fay\core\Response;
use apidoc\models\tables\Outputs;

class ApiController extends AdminController{
	/**
	 * box列表
	 */
	public $boxes = array(
		array('name'=>'router', 'title'=>'路由'),
		array('name'=>'category', 'title'=>'分类'),
		array('name'=>'http_method', 'title'=>'HTTP请求方式'),
		array('name'=>'need_login', 'title'=>'是否需要登录'),
		array('name'=>'since', 'title'=>'自从'),
		array('name'=>'inputs', 'title'=>'请求参数'),
		array('name'=>'outputs', 'title'=>'响应参数'),
		array('name'=>'sample_response', 'title'=>'响应示例'),
	);
	
	/**
	 * 默认box排序
	*/
	public $default_box_sort = array(
		'side'=>array(
			'router', 'category', 'http_method', 'need_login', 'since'
		),
		'normal'=>array(
			'inputs', 'outputs', 'sample_response'
		),
	);
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'api';
	}
	
	public function index(){
		$this->layout->subtitle = 'API列表';
		
		$cat_id = $this->input->get('cat_id', 'intval', 0);
		
		if($this->checkPermission('admin/api/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/api/create', array(
					'cat_id'=>$cat_id
				)),
				'text'=>'新增API',
			);
		}
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_api_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('router', 'status', 'category', 'since', 'create_time'),
			'display_name'=>'nickname',
			'display_time'=>'short',
			'page_size'=>10,
		);
		$this->form('setting')
			->setModel(Setting::model())
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key
			))
			->setJsModel('setting');
		
		$this->view->enabled_boxes = $this->getEnabledBoxes('admin_api_boxes');
		
		$sql = new Sql();
		$sql->from(array('a'=>'apidoc_apis'));
		
		if(in_array('category', $_settings['cols'])){
			$sql->joinLeft(array('c'=>'categories'), 'a.cat_id = c.id', 'title AS cat_title');
		}
		
		if(in_array('user', $_settings['cols'])){
			$sql->joinLeft(array('u'=>'users'), 'a.user_id = u.id', 'username,nickname,realname');
		}
		
		//根据分类搜索
		if($cat_id){
			$sql->where('a.cat_id = ?', $cat_id);
		}
		
		//根据状态搜索
		if($this->input->get('status') !== null){
			$sql->where('a.status = ?', $this->input->get('status', 'intval'));
		}
		
		//时间段
		if($this->input->get('start_time')){
			$sql->where("a.{$this->input->get('time_field')} > ?", $this->input->get('start_time', 'strtotime'));
		}
		if($this->input->get('end_time')){
			$sql->where("a.{$this->input->get('time_field')} < ?", $this->input->get('end_time', 'strtotime'));
		}
		
		//关键词搜索
		if($this->input->get('keywords')){
			if(in_array($this->input->get('keywords_field'), array('user_id'))){
				$sql->where("a.{$this->input->get('keywords_field')} = ?", $this->input->get('keywords', 'intval'));
			}else{
				$sql->where("a.{$this->input->get('keywords_field')} LIKE ?", '%'.$this->input->get('keywords', 'trim').'%');
			}
		}
		
		//排序
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('a.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 20),
			'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
		));
		
		//各状态记录数
		$sql->reset();
		$status_counts = $sql->from(array('a'=>'apidoc_apis'), array('status', 'COUNT(*) AS count'))
			->group('a.status')
			->fetchAll();
		$this->view->status_counts = ArrayHelper::column($status_counts, 'count', 'status');
		
		//查找api分类
		$this->view->cats = Category::model()->getTree('_system_api');
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '新增API';
		if($this->checkPermission('admin/api/index')){
			$this->layout->sublink = array(
				'uri'=>array('admin/api/index'),
				'text'=>'API列表',
			);
		}
		
		$this->form()->setModel(Apis::model());
		
		//启用的编辑框
		$_setting_key = 'admin_api_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = Apis::model()->fillData($this->input->post(), true, 'insert');
			$data['create_time'] = $this->current_time;
			$data['last_modified_time'] = $this->current_time;
			$data['user_id'] = $this->current_user;
			$api_id = Apis::model()->insert($data);
			
			$inputs = $this->input->post('inputs');
			foreach($inputs as $i){
				$input = Inputs::model()->fillData($i, true, 'insert');
				$input['api_id'] = $api_id;
				$input['create_time'] = $this->current_time;
				$input['last_modified_time'] = $this->current_time;
				Inputs::model()->insert($input);
			}
			
			Response::notify('success', 'API添加成功', array('admin/api/edit', array(
				'id'=>$api_id,
			)));
		}
		
		//分类树
		$this->view->cats = Category::model()->getTree('_system_api');
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_api_box_sort');
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
		
		//输入参数表单规则
		$this->form('input-parameter')->setModel(Inputs::model());
		
		//输出参数表单规则
		$this->form('output')->setModel(Outputs::model())
			->setRule(array('model_name', 'required'))
			->setRule(array('model_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
			->setRule(array('model_name', 'ajax', array('url'=>array('admin/model/is-name-exist'))))
			->setLabels(array(
				'model_name'=>'模型名称',
			));
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑API';
		if($this->checkPermission('admin/api/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/api/create'),
				'text'=>'添加API',
			);
		}
		
		$api_id = $this->input->get('id', 'intval');
		$this->form()->setModel(Apis::model());
		
		//启用的编辑框
		$_setting_key = 'admin_api_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			$data = Apis::model()->fillData($this->input->post(), true, 'update');
			$data['last_modified_time'] = $this->current_time;
			Apis::model()->update($data, $api_id);
			
			$inputs = $this->input->post('inputs');
			//删除已被删除的输入参数
			if($inputs){
				Inputs::model()->delete(array(
					'api_id = ?'=>$api_id,
					'id NOT IN (?)'=>array_keys($inputs),
				));
			}else{
				Inputs::model()->delete(array(
					'api_id = ?'=>$api_id,
				));
			}
			//获取已存在的输入参数
			$old_input_parameter_ids = Inputs::model()->fetchCol('id', array(
				'api_id = ?'=>$api_id,
			));
			foreach($inputs as $input_parameter_id => $input){
				if(in_array($input_parameter_id, $old_input_parameter_ids)){
					$input = Inputs::model()->fillData($input, true, 'update');
					$input['last_modified_time'] = $this->current_time;
					Inputs::model()->update($input, $input_parameter_id);
				}else{
					$input = Inputs::model()->fillData($input, true, 'insert');
					$input['api_id'] = $api_id;
					$input['create_time'] = $this->current_time;
					$input['last_modified_time'] = $this->current_time;
					Inputs::model()->insert($input);
				}
			}
			
			Response::notify('success', 'API编辑成功', array('admin/api/edit', array(
				'id'=>$api_id,
			)));
		}
		
		$api = Apis::model()->find($api_id);
		$this->form()->setData($api);
		
		//原输入参数
		$this->view->inputs = Inputs::model()->fetchAll('api_id = '.$api_id, '*', 'required DESC, name ASC');
		
		//分类树
		$this->view->cats = Category::model()->getTree('_system_api');
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_api_box_sort');
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
		
		//输入参数表单规则
		$this->form('input-parameter')->setModel(Inputs::model());
		
		//输出参数表单规则
		$this->form('output')->setModel(Outputs::model())
			->setRule(array('model_name', 'required'))
			->setRule(array('model_name', 'exist', array('table'=>'apidoc_models', 'field'=>'name')))
			->setRule(array('model_name', 'ajax', array('url'=>array('admin/model/is-name-exist'))))
			->setLabels(array(
				'model_name'=>'模型名称',
			));
		
		$this->view->render();
	}
	
	/**
	 * 分类管理
	 */
	public function cat(){
		$this->layout->current_directory = 'api';
		$this->layout->_setting_panel = '_setting_cat';
		
		$_setting_key = 'admin_api_cat';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'default_dep'=>2,
		);
		$this->form('setting')
			->setModel(Setting::model())
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key
			))
			->setJsModel('setting');
		
		$this->layout->subtitle = 'API分类';
		$this->view->cats = Category::model()->getTree('_system_api');
		$root_node = Category::model()->getByAlias('_system_api', 'id');
		$this->view->root = $root_node['id'];
		
		if($this->checkPermission('admin/api/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加分类',
				'html_options'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'API',
					'data-id'=>$root_node['id'],
				),
			);
		}
		
		$this->view->render();
	}
}