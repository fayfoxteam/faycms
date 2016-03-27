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
use fay\core\Sql;
use fay\models\tables\FeedMeta;
use fay\common\ListView;
use fay\helpers\Date;

class FeedController extends AdminController{
	/**
	 * box列表
	 */
	public $boxes = array(
		array('name'=>'publish_time', 'title'=>'发布时间'),
		array('name'=>'tags', 'title'=>'标签'),
		array('name'=>'files', 'title'=>'附件'),
		array('name'=>'location', 'title'=>'地理位置信息'),
		array('name'=>'timeline', 'title'=>'时间轴'),
	);
	
	/**
	 * 默认box排序
	*/
	public $default_box_sort = array(
		'side'=>array(
			'publish_time', 'timeline', 'location',
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
		if($this->checkPermission('admin/feed/index')){
			$this->layout->sublink = array(
				'uri'=>array('admin/feed/index'),
				'text'=>'所有动态',
			);
		}
		
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
		$this->layout->subtitle = '编辑动态';
		if($this->checkPermission('admin/feed/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/feed/create'),
				'text'=>'发布动态',
			);
		}
		
		$this->form()->setModel(Feeds::model())
			->setModel(FeedsFiles::model())
			->setModel(FeedExtra::model());
		
		//启用的编辑框
		$_setting_key = 'admin_feed_boxes';
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		$feed_id = $this->input->get('id', 'intval');
		
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
				
			//时间轴特殊处理
			if(in_array('timeline', $enabled_boxes)){
				if(empty($data['timeline'])){
					$data['timeline'] = $data['publish_time'];
				}else{
					$data['timeline'] = strtotime($data['timeline']);
				}
			}
			
			$extra = array();
			
			//动态扩展表
			$extra['extra'] = FeedExtra::model()->fillData($this->input->post());
			
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
			
			FeedService::model()->update($feed_id, $data, $extra);
			
			$this->actionlog(Actionlogs::TYPE_FEED, '编辑动态', $feed_id);
			Response::notify('success', '动态编辑成功', false);
		}
		
		$sql = new Sql();
		$feed = $sql->from(array('f'=>'feeds'), Feeds::model()->getFields())
			->joinLeft(array('fm'=>'feed_meta'), 'f.id = fm.feed_id', FeedMeta::model()->getFields('feed_id'))
			->joinLeft(array('fe'=>'feed_extra'), 'f.id = fe.feed_id', FeedExtra::model()->getFields('feed_id'))
			->where('f.id = ' . $feed_id)
			->fetchRow()
		;
		
		$feed['publish_time'] = date('Y-m-d H:i:s', $feed['publish_time']);
		$feed['timeline'] = date('Y-m-d H:i:s', $feed['timeline']);
		$feed['create_time'] = Date::format($feed['create_time']);
		
		//动态对应标签
		$tags = $sql->from(array('ft'=>'feeds_tags'), '')
			->joinLeft(array('t'=>'tags'), 'ft.tag_id = t.id', 'title')
			->where('ft.feed_id = ' . $feed_id)
			->fetchAll();
		$tags_arr = array();
		foreach($tags as $t){
			$tags_arr[] = $t['title'];
		}
		$this->form()->setData(array('tags'=>implode(',', $tags_arr)));
		
		//配图
		$this->view->files = FeedsFiles::model()->fetchAll(array(
			'feed_id = ?'=>$feed_id,
		), 'file_id,description', 'sort');
		
		$this->form()->setData($feed, true);
		
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
		$this->layout->subtitle = '所有动态';
		
		if($this->checkPermission('admin/feed/create')){
			$this->layout->sublink = array(
				'uri'=>array('admin/feed/create'),
				'text'=>'发布动态',
			);
		}
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_feed_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('user', 'status', 'publish_time', 'last_modified_time', 'create_time', 'timeline'),
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
		
		$this->view->enabled_boxes = $this->getEnabledBoxes('admin_feed_boxes');
		
		$sql = new Sql();
		$sql->from(array('f'=>'feeds'))
			->joinLeft(array('fm'=>'feed_meta'), 'f.id = fm.feed_id', FeedMeta::model()->getFields('feed_id'))
			->joinLeft(array('fe'=>'feed_extra'), 'f.id = fe.feed_id', FeedExtra::model()->getFields('feed_id'))
		;
		
		if(in_array('user', $_settings['cols'])){
			$sql->joinLeft(array('u'=>'users'), 'f.user_id = u.id', 'username,nickname,realname');
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('f.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 20),
			'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
		));
		
		$this->view->render();
	}
	
	/**
	 * 返回各状态下的动态数
	 */
	public function getCounts(){
		$data = array(
			'all'=>\cms\models\Post::model()->getCount(),
			'published'=>\cms\models\Post::model()->getCount(Feeds::STATUS_PUBLISHED),
			'draft'=>\cms\models\Post::model()->getCount(Feeds::STATUS_DRAFT),
			'deleted'=>\cms\models\Post::model()->getDeletedCount(),
		);
		
		if($this->post_review){
			$data['pending'] = \cms\models\Post::model()->getCount(Feeds::STATUS_PENDING);
			$data['reviewed'] = \cms\models\Post::model()->getCount(Feeds::STATUS_REVIEWED);
		}
		
		Response::json($data);
	}
}