<?php
namespace cms\library;

use fay\core\Controller;
use fay\core\Uri;
use fay\helpers\Request;
use fay\models\tables\Users;
use fay\models\tables\Actions;
use fay\models\tables\Actionlogs;
use fay\models\Setting;
use fay\core\Response;
use fay\models\Menu;
use fay\core\HttpException;
use fay\models\Flash;
use fay\models\tables\Roles;

class AdminController extends Controller{
	public $layout_template = 'admin';
	/**
	 * 当前用户id（users表中的ID）
	 * @var int
	 */
	public $current_user = 0;
	
	public $_left_menu = array();
	
	public $_top_nav = array(
		array(
			'label'=>'站点首页',
			'icon'=>'fa fa-home',
			'router'=>null,
			'target'=>'_blank',
		),
		array(
			'label'=>'控制台',
			'icon'=>'fa fa-dashboard',
			'router'=>'admin/index/index',
		),
		array(
			'label'=>'Tools',
			'icon'=>'fa fa-wrench',
			'router'=>'tools',
			'roles'=>Roles::ITEM_SUPER_ADMIN,
		),
	);
	
	public function __construct(){
		parent::__construct();
		//重置session_namespace
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		
		//验证session中是否有值
		if(!$this->session->get('admin')){
			Response::redirect('admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		//设置当前用户id
		$this->current_user = $this->session->get('id');
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';

		//权限判断
		if(!$this->checkPermission(Uri::getInstance()->router)){
			throw new HttpException('您无权限做此操作', 403);
		}
		
		$this->_left_menu = Menu::model()->getTree('_admin_main');
	}
	
	/**
	 * 表单验证出错后的报错渲染
	 * @param array $check
	 * @param bool $return
	 * @return string
	 */
	public function showDataCheckError($check, $return = false){
		$html = '';
		foreach($check as $c){
			$html .= "<p>{$c[2]}</p>";
		}
		if($return){
			return $html;
		}else{
			Flash::set($html);
		}
	}
	
	/**
	 * 后台管理员操作日志
	 * @param string $type
	 * @param string $note
	 * @param int $refer 引用，例如操作为“编辑文章”则该字段为文章id
	 */
	public function actionlog($type, $note, $refer = 0){
		Actionlogs::model()->insert(array(
			'user_id'=>$this->current_user,
			'create_time'=>$this->current_time,
			'ip_int'=>Request::ip2int($this->ip),
			'type'=>$type,
			'note'=>$note,
			'refer'=>$refer,
		));
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
	 * @param array $sub_menu
	 * @param string $directory 菜单组别名
	 * @param int $offset 偏移，从0开始，若为负数，则从末尾倒数
	 */
	public function addMenuItem($sub_menu, $directory, $offset = -1){
		foreach($this->_left_menu as $k => &$menu){
			if($menu['alias'] == $directory){
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
			if($menu['alias'] == $directory){
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