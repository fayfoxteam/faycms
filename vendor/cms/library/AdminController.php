<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Uri;
use fay\helpers\RequestHelper;
use fay\models\tables\Users;
use fay\models\tables\Actions;
use fay\models\tables\Actionlogs;
use fay\models\Setting;
use fay\core\Response;

class AdminController extends Controller{
	public $layout_template = 'admin';
	/**
	 * 当前用户id（users表中的ID）
	 * @var int
	 */
	public $current_user = 0;
	
	/**
	 * 检查过被阻止的路由
	 */
	protected $_deny_routers = array();
	
	public $_left_menu = array(
		array(
			'label'=>'权限',
			'directory'=>'role',
			'sub'=>array(
				array('label'=>'角色列表','router'=>'admin/role/index',),
				array('label'=>'添加角色','router'=>'admin/role/create',),
				array('label'=>'所有权限','router'=>'admin/action/index',),
				array('label'=>'权限分类','router'=>'admin/category/action',),
			),
			'icon'=>'icon-gavel',
		),
		array(
			'label'=>'用户管理',
			'directory'=>'user',
			'sub'=>array(
				array('label'=>'所有用户','router'=>'admin/user/index',),
				array('label'=>'添加用户','router'=>'admin/user/create',),
				array('label'=>'所有管理员','router'=>'admin/operator/index',),
				array('label'=>'添加管理员','router'=>'admin/operator/create',),
			),
			'icon'=>'icon-users',
		),
		array(
			'label'=>'留言',
			'directory'=>'message',
			'sub'=>array(
				array('label'=>'文章评论','router'=>'admin/comment/index',),
				array('label'=>'联系我们','router'=>'admin/contact/index',),
				array('label'=>'会话','router'=>'admin/chat/index',),
			),
			'icon'=>'icon-comments',
		),
		array(
			'label'=>'文章',
			'directory'=>'post',
			'sub'=>array(
				array('label'=>'所有文章','router'=>'admin/post/index',),
				array('label'=>'分类（发布）','router'=>'admin/post/cat',),
				array('label'=>'标签','router'=>'admin/tag/index',),
				array('label'=>'关键词','router'=>'admin/keyword/index',),
			),
			'icon'=>'icon-edit',
		),
		array(
			'label'=>'页面',
			'directory'=>'page',
			'sub'=>array(
				array('label'=>'所有页面','router'=>'admin/page/index',),
				array('label'=>'添加页面','router'=>'admin/page/create',),
				array('label'=>'分类','router'=>'admin/page/cat',),
			),
			'icon'=>'icon-bookmark',
		),
		array(
			'label'=>'导航栏',
			'directory'=>'menu',
			'sub'=>array(
				array('label'=>'自定义导航','router'=>'admin/menu/index',),
			),
			'icon'=>'icon-map-marker',
		),
		array(
			'label'=>'商品',
			'directory'=>'goods',
			'sub'=>array(
				array('label'=>'所有商品','router'=>'admin/goods/index',),
				array('label'=>'分类（发布）','router'=>'admin/category/goods',),
			),
			'icon'=>'icon-shopping-cart',
		),
		array(
			'label'=>'优惠卷',
			'directory'=>'voucher',
			'sub'=>array(
				array('label'=>'所有优惠卷','router'=>'admin/voucher/index',),
				array('label'=>'添加优惠卷','router'=>'admin/voucher/create',),
			),
			'icon'=>'icon-gift',
		),
		array(
			'label'=>'友情链接',
			'directory'=>'link',
			'sub'=>array(
				array('label'=>'所有友链','router'=>'admin/link/index',),
				array('label'=>'添加友链','router'=>'admin/link/create',),
				array('label'=>'分类','router'=>'admin/link/cat',),
			),
			'icon'=>'icon-chain',
		),
		array(
			'label'=>'分类',
			'directory'=>'cat',
			'sub'=>array(
				array('label'=>'所有分类','router'=>'admin/category/index',),
			),
			'icon'=>'icon-sitemap',
		),
		array(
			'label'=>'站点',
			'directory'=>'site',
			'sub'=>array(
				array('label'=>'站点参数','router'=>'admin/site/options',),
				array('label'=>'参数列表','router'=>'admin/option/index',),
				array('label'=>'系统日志','router'=>'admin/log/index',),
				array('label'=>'小工具','router'=>'admin/widget/instances',),
				array('label'=>'所有小工具','router'=>'admin/widget/index',),
			),
			'icon'=>'icon-cog',
		),
		array(
			'label'=>'访问统计',
			'directory'=>'analyst',
			'sub'=>array(
				array('label'=>'访客统计','router'=>'admin/analyst/visitor',),
				array('label'=>'访问日志','router'=>'admin/analyst/views',),
				array('label'=>'页面PV量','router'=>'admin/analyst/pv',),
				array('label'=>'站点管理','router'=>'admin/analyst-site/index',),
				array('label'=>'蜘蛛爬行记录','router'=>'admin/analyst/spiderlog',),
			),
			'icon'=>'icon-chart',
		),
		array(
			'label'=>'文件',
			'directory'=>'file',
			'sub'=>array(
				array('label'=>'所有文件','router'=>'admin/file/index',),
				array('label'=>'上传文件','router'=>'admin/file/do-upload',),
			),
			'icon'=>'icon-files',
		),
		array(
			'label'=>'系统通知',
			'directory'=>'notification',
			'sub'=>array(
				array('label'=>'我的消息','router'=>'admin/notification/my',),
				array('label'=>'发送消息','router'=>'admin/notification/create',),
				array('label'=>'消息分类','router'=>'admin/notification/cat',),
			),
			'icon'=>'icon-comment',
		),
		array(
			'label'=>'提醒',
			'directory'=>'template',
			'sub'=>array(
				array('label'=>'添加模版','router'=>'admin/template/create',),
				array('label'=>'模版管理','router'=>'admin/template/index',),
			),
			'icon'=>'icon-envelope',
		),
		array(
			'label'=>'试题',
			'directory'=>'exam-question',
			'sub'=>array(
				array('label'=>'试题库','router'=>'admin/exam-question/index',),
				array('label'=>'添加试题','router'=>'admin/exam-question/create',),
				array('label'=>'试题分类','router'=>'admin/exam-question/cat',),
			),
			'icon'=>'icon-book',
		),
		array(
			'label'=>'试卷',
			'directory'=>'exam-paper',
			'sub'=>array(
				array('label'=>'试卷列表','router'=>'admin/exam-paper/index',),
				array('label'=>'组卷','router'=>'admin/exam-paper/create',),
				array('label'=>'阅卷','router'=>'admin/exam-exam/index',),
				array('label'=>'试卷分类','router'=>'admin/exam-paper/cat',),
			),
			'icon'=>'icon-edit',
		),
	);
	
	public $_top_nav = array(
		array(
			'label'=>'站点首页',
			'icon'=>'icon-home',
			'router'=>null,
			'target'=>'_blank',
		),
		array(
			'label'=>'控制台',
			'icon'=>'icon-dashboard',
			'router'=>'admin/index/index',
		),
		array(
			'label'=>'Tools',
			'icon'=>'icon-wrench',
			'router'=>'tools',
			'role'=>Users::ROLE_SUPERADMIN,
		),
	);
	
	public function __construct(){
		parent::__construct();
		//重置session_namespace
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		//验证session中是否有值
		if(!$this->session->get('role') || $this->session->get('role') <= Users::ROLE_SYSTEM){
			Response::redirect('admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		//设置当前用户id
		$this->current_user = $this->session->get('id');
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';

		//权限判断
		if($this->session->get('role') != Users::ROLE_SUPERADMIN){
			$uri = Uri::getInstance();
			$action = Actions::model()->fetchRow(array('router = ?'=>$uri->router), 'is_public');
			//没设置权限的路由均默认为可访问路由
			if($action && !$action['is_public'] && !in_array($uri->router, $this->session->get('actions', array()))){
				Response::output('error', '您无权限做此操作');
			}
		}
	}
	
	public function showDataCheckError($check, $return = false){
		$html = '';
		foreach($check as $c){
			$html .= "<p>{$c[2]}</p>";
		}
		if($return){
			return $html;
		}else{
			$this->flash->set($html);
		}
	}
	
	public function actionlog($type, $note, $refer = 0){
		Actionlogs::model()->insert(array(
			'user_id'=>$this->current_user,
			'create_time'=>$this->current_time,
			'ip_int'=>RequestHelper::ip2int($this->ip),
			'type'=>$type,
			'note'=>$note,
			'refer'=>$refer,
		));
	}
	
	public function checkPermission($router){
		if($this->session->get('role') == Users::ROLE_SUPERADMIN){
			//超级管理员无限制
			return true;
		}else if(in_array($router, $this->session->get('actions'))){
			//用户有此权限
			return true;
		}else{
			if(in_array($router, $this->_deny_routers)){
				//已经检查过此路由为不可访问路由
				return false;
			}
			$action = Actions::model()->fetchRow(array('router = ?'=>$router), 'is_public');
			if($action['is_public']){
				//此路由为公共路由
				return true;
			}else if(!$action){
				//此路由并不在权限路由列表内，视为公共路由
				return true;
			}
		}
		$this->_deny_routers[] = $router;
		return false;
	}
	
	/**
	 * 添加一个菜单组，一次只能添加一组
	 */
	public function addMenuTeam($menu, $offset = null){
		if($offset === null){
			$this->_left_menu[] = $menu;
		}else{
			array_splice($this->_left_menu, $offset, 0, array($menu));
		}
	}
	
	/**
	 * 在已有的菜单组中添加一个菜单项
	 */
	public function addMenuItem($sub_menu, $directory, $offset){
		foreach($this->_left_menu as $k => &$menu){
			if($menu['directory'] == $directory){
				array_splice($menu['sub'], $offset, 0, array($sub_menu));
			}
		}
	}
	
	/**
	 * 根据directory删除一组菜单，若指定index，则只删除指定的某个菜单项。
	 * index从0开始
	 */
	public function removeMenuTeam($directory, $index = null){
		foreach($this->_left_menu as $k => &$menu){
			if($menu['directory'] == $directory){
				if($index === null){
					unset($this->_left_menu[$k]);
					break;
				}else{
					unset($menu['sub'][$index]);
				}
			}
		}
	}
	
	/**
	 * 添加一个顶部菜单
	 */
	public function addTopNav($menu, $offset = null){
		if($offset === null){
			$this->_top_nav[] = $menu;
		}else{
			array_splice($this->_top_nav, $offset, 0, array($menu));
		}
	}
	
	/**
	 * 将boxes二维数组转换为仅包含name值的一维数组返回
	 * @return multitype:|multitype:Ambigous <>
	 */
	protected function getBoxNames(){
		if(!$this->boxes)return array();
		$names = array();
		foreach($this->boxes as $box){
			$names[] = $box['name'];
		}
		return $names;
	}
	
	/**
	 * 获取用户启用的boxes
	 * 
	 * @param null|string|array $settings 若为null 会去View中获取key
	 * 若是string 视为key
	 * 若是array 视为传入配置数组
	 */
	protected function getEnabledBoxes($settings = null){
		$settings === null && $settings = $this->view->_setting_key;
		if(!is_array($settings)){
			$settings = Setting::model()->get($settings);
		}
		if(!empty($settings['boxes'])){
			return array_intersect($this->getBoxNames(), isset($settings['boxes']) ? $settings['boxes'] : array());
		}else{
			return $this->getBoxNames();
		}
	}
	
	/**
	 * 添加一个box
	 */
	public function addBox($box, $offset = null){
		if($offset === null){
			$this->boxes[] = $box;
		}else{
			$this->boxes = array_splice($this->boxes, $offset, 0, array($box));
		}
		
	}
	
	/**
	 * 根据box name删除一个box
	 */
	public function removeBox($name){
		if(isset($this->boxes) && is_array($this->boxes)){
			foreach($this->boxes as $key=>$box){
				if($box['name'] == $name){
					unset($this->boxes[$key]);
					break;
				}
			}
		}
	}
}