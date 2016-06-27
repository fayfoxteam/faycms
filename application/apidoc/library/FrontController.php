<?php
namespace apidoc\library;

use fay\core\Controller;
use fay\helpers\Request;
use fay\models\tables\SpiderLogs;
use fay\services\Category;
use apidoc\models\tables\Apis;
use fay\helpers\Html;

class FrontController extends Controller{
	public $layout_template = 'frontend';
	
	public $_left_menu = array();
	
	public $_top_nav = array();
	
	public function __construct(){
		parent::__construct();
		
		//设置当前用户id
		$this->current_user = \F::session()->get('user.id', 0);
		
		if($spider = Request::isSpider()){//如果是蜘蛛，记录蜘蛛日志
			SpiderLogs::model()->insert(array(
				'spider'=>$spider,
				'url'=>'http://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'],
				'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
				'ip_int'=>Request::ip2int($this->ip),
				'create_time'=>$this->current_time,
			));
		}
		
		$this->layout->assign(array(
			'current_directory'=>'',
			'title'=>'',
			'subtitle'=>'',
			'api_id'=>0,
		));
		
		$this->_left_menu = $this->getLeftMenu();
	}
	
	private function getLeftMenu(){
		$api_cats = Category::service()->getNextLevel('_system_api', array('id', 'alias', 'title', 'description'));
		$apis = Apis::model()->fetchAll(array(), 'id,title,router,cat_id', 'cat_id');
		$menus = array();
		foreach($api_cats as $c){
			$menu = array(
				'id'=>0,
				'alias'=>$c['alias'],
				'title'=>$c['title'],
				'css_class'=>$c['description'],
				'link'=>'javascript:;',
				'target'=>'',
				'children'=>array(),
			);
			
			$start = false;
			foreach($apis as $k => $a){
				if($a['cat_id'] == $c['id']){
					$start = true;
					$menu['children'][] = array(
						'id'=>$a['id'],
						'alias'=>'',
						'title'=>$a['router'] . '<br>' . Html::encode($a['title']),
						'css_class'=>'',
						'link'=>'api/' . $a['id'],
						'target'=>'',
						'children'=>array(),
					);
					unset($apis[$k]);
				}else if($start){
					break;
				}
			}
			
			$menus[] = $menu;
		}
		
		return $menus;
	}
}