<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\Setting;
use apidoc\models\tables\Apis;
use fay\helpers\ArrayHelper;
use fay\models\Category;

class ApiController extends AdminController{
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
			'cols'=>array('router', 'status', 'category', 'version', 'create_time'),
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
	

	
	/**
	 * 发布动态
	 */
	public function create(){
		$this->layout->subtitle = '新增接口';
		if($this->checkPermission('admin/api/index')){
			$this->layout->sublink = array(
				'uri'=>array('admin/api/index'),
				'text'=>'API列表',
			);
		}
		
		$this->view->render();
	}
}