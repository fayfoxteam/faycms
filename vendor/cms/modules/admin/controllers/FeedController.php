<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Feeds;
use fay\models\tables\FeedsFiles;
use fay\models\Setting;
use fay\services\Feed as FeedService;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\models\tables\FeedExtra;

class FeedController extends AdminController{
	/**
	 * box列表
	 */
	public $boxes = array(
		array('name'=>'publish_time', 'title'=>'发布时间'),
		array('name'=>'tags', 'title'=>'标签'),
		array('name'=>'files', 'title'=>'附件'),
		array('name'=>'location', 'title'=>'地理位置信息'),
	);
	
	/**
	 * 默认box排序
	*/
	public $default_box_sort = array(
		'side'=>array(
			'publish_time', 'location',
		),
		'normal'=>array(
			'tags', 'files',
		),
	);
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'feed';
	}
	
	/**
	 * 发布动态
	 */
	public function create(){
		$this->layout->subtitle = '发布动态';
		
		$this->form()->setModel(Feeds::model())
			->setModel(FeedsFiles::model())
			->setModel(FeedExtra::model());
		
		//启用的编辑框
		$_setting_key = 'admin_feed_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			//添加feeds表
			$data = Feeds::model()->fillData($this->input->post());
			
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
			
			$feed_id = FeedService::model()->create($data, $extra, $this->current_user);
			
			$this->actionlog(Actionlogs::TYPE_FEED, '添加动态', $feed_id);
			Response::notify('success', '动态发布成功', array('admin/feed/edit', array(
				'id'=>$feed_id,
			)));
		}
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_feed_box_sort');
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
		
		$this->view->render();
	}
	
	public function edit(){
		$this->form()->setModel(Feeds::model())
			->setModel(FeedsFiles::model())
			->setModel(FeedExtra::model());
		
		//启用的编辑框
		$_setting_key = 'admin_feed_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		if($this->input->post() && $this->form()->check()){
			
		}
		
		//可配置信息
		$_box_sort_settings = Setting::model()->get('admin_feed_box_sort');
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
		
		$this->view->render();
	}
	
	public function index(){
		
	}
}