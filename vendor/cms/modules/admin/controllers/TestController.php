<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Validator;
use fay\helpers\Html;
use fay\core\Loader;
use fay\models\tables\Posts;
use fay\core\Db;

class TestController extends AdminController{
	public function valid(){
		$v = new Validator();
		$v->setLables(array(
			'email'=>'邮箱',
			'i'=>'Int',
			'f'=>'Float',
		));
		pr($v->check(array(
			array('email', 'email'),
			array('m', 'mobile'),
			array('u', 'url'),
			array('zh', 'chinese'),
			array('d', 'datetime'),
			array(array('e', 'email'), 'email'),
			array('r', 'required', array('enableEmpty'=>false)),
			array('i', 'int', array('max'=>10, 'min'=>8, 'too_big'=>'太大了测试')),
			array('f', 'float', array('length'=>5, 'decimal'=>2, 'max'=>88.88, 'min'=>-10000)),
			array('s', 'string', array('max'=>10, 'format'=>'/\d+/')),
			array('unique', 'unique', array('table'=>'users', 'field'=>'username', 'except'=>'id')),
			array('exist', 'exist', array('table'=>'users', 'field'=>'username')),
			array('r', 'range', array('range'=>array('a', 'bb', 'ccc'), 'not'=>true)),
			array('c', 'compare', array('compare_attribute'=>'id', 'operator'=>'==', 'message'=>'{$attribute}值不对')),
		)));
	}
	
	public function jsvalid(){
		$this->view->render();
	}
	
	public function jsvalidForms(){
		$this->view->render();
	}
	
	public function phpvalid(){
		$rules = array(
			array('username', 'string', array('min'=>2, 'max'=>5, 'format'=>'alias')),
			array('username', 'required'),
			array('role', 'range', array('range'=>array('2', '3'))),
			array('status', 'int', array('min'=>1, 'max'=>5)),
			array('status', 'required'),
			array('refer', 'string', array('min'=>2, 'max'=>5)),
			array('cat_id', 'int', array('min'=>2, 'max'=>4)),
			array('cat_id', 'range', array('range'=>array('2', '3'))),
			array('username', 'unique', array('table'=>'users', 'field'=>'username', 'ajax'=>array('tools/user/is-username-not-exist'))),
			array('datetime', 'datetime', array('int'=>true)),
		);
		
		if($this->input->post()){
			$valid = $this->form()->setData($this->input->post())
				->setRules($rules)
				->setFilters(array('datetime'=>'strtotime'))
				->check();
// 			$valid = $this->form()->setModel(Users::model())
// 				->setData($this->input->post())
// 				->check(true);
			if($valid === true){
				pr($this->input->post());
			}else{
				//Flash::set(pr($valid, true, true));
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->render();
	}
	
	public function tag(){
// 		echo Html::tag('a', array(
// 			'href'=>'http://www.baidu.com',
// 			'before'=>array(
// 				'tag'=>'em',
// 				'text'=>'*',
// 				'class'=>'fc-red',
// 			),
// 			'append'=>'---',
// 			'prepend'=>array(
// 				'tag'=>'time',
// 				'text'=>'2014-01-06',
// 				'after'=>array(
// 					'tag'=>'br',
// 				)
// 			),
// 			'wrapper'=>array(
// 				'tag'=>'div',
// 				'wrapper'=>'div',
// 				'class'=>'inner-div',
// 			)
// 		), array(
// 			array(
// 				'tag'=>'span',
// 				'text'=>'链接',
// 			),
// 			array(
// 				'tag'=>'span',
// 				'text'=>'链接2',
// 			),
// 		));
// 		echo "\r\n\r\n\r\n";
		
		echo Html::link('链接', array('admin/user/index'), array(
			'prepend'=>'-->'
		));
		echo "\r\n<br>\r\n<br>\r\n";
		echo Html::tag('a', array(
			'href'=>'javascript',
			'prepend'=>'{prepend}',
			'before'=>'{before}',
		), 'tag生成的链接');
		echo "\r\n<br>\r\n<br>\r\n";
		
		
		
		
		
		
		/**
		 * 生成完整表单
		 */
// 		echo Html::tag('form', array(
// 			'method'=>'post',
// 		), array(
// 			array(
// 				'tag'=>'fieldset',
// 				'class'=>'form-field',
// 				'text'=>array(
// 					array(
// 						'tag'=>'label',
// 						'class'=>'title',
// 						'text'=>'名称',
// 						'append'=>array(
// 							'tag'=>'em',
// 							'class'=>'fc-red',
// 							'text'=>'*',
// 						)
// 					),
// 					array(
// 						'tag'=>'input',
// 						'type'=>'text',
// 						'name'=>'title',
// 						'class'=>'w300',
// 					),
// 					array(
// 						'tag'=>'p',
// 						'text'=>'例如：百度',
// 						'class'=>'description',
// 					)
// 				)
// 			),
// 			array(
// 				'tag'=>'fieldset',
// 				'class'=>'form-field',
// 				'text'=>array(
// 					array(
// 						'tag'=>'label',
// 						'class'=>'title',
// 						'text'=>'打开方式',
// 					),
// 					array(
// 						'tag'=>'p',
// 						'text'=>array(
// 							'tag'=>'input',
// 							'type'=>'radio',
// 							'name'=>'target',
// 							'value'=>'_blank',
// 							'checked'=>'checked',
// 							'text'=>'_blank — 新窗口或新标签。',
// 							'wrapper'=>array(
// 								'tag'=>'label',
// 							)
// 						),
// 					),
// 					array(
// 						'tag'=>'p',
// 						'text'=>array(
// 							'tag'=>'input',
// 							'type'=>'radio',
// 							'name'=>'target',
// 							'value'=>'_top',
// 							'text'=>'_top — 不包含框架的当前窗口或标签。',
// 							'wrapper'=>array(
// 								'tag'=>'label',
// 							)
// 						),
// 					),
// 					array(
// 						'tag'=>'p',
// 						'text'=>array(
// 							'tag'=>'input',
// 							'type'=>'radio',
// 							'name'=>'target',
// 							'value'=>'_none',
// 							'text'=>'_none — 同一窗口或标签。',
// 							'wrapper'=>array(
// 								'tag'=>'label',
// 							)
// 						),
// 					),
// 					array(
// 						'tag'=>'p',
// 						'text'=>'为您的链接选择目标框架。',
// 						'class'=>'description',
// 					)
// 				)
// 			),
// 		));
	}
	
	public function debug(){
		$this->layout_template = null;
		$this->view->render();
	}
	
	public function redis(){
		Loader::vendor('predis/predis/lib/Predis/Autoloader');
		\Predis\Autoloader::register();
		
		$client = new \Predis\Client('tcp://114.215.134.73:6379');
		$client->set('foo', 'bar');
		$value = $client->get('foo');
		
		var_dump(Users9::model());
	}
	
	public function in(){
		//$ids = array(10086,20000,130001,200133,349985,858372,1139822,2993814,3482713,3898234);
		$ids = array(/* 10086,20000,130001,200133,349985,858372,1139822,2993814,3482713,3898234, */
			30000,30001,30002,30003,30004,30005,30006,30007,30008,30009);
		$start = microtime(true);
		$posts = \fay\models\tables\Posts::model()->fetchAll('id IN ('.implode(',', $ids).')');
		//\fay\core\Db::getInstance()->fetchAll('SELECT id,title FROM posts_0 WHERE id IN ('.implode(',', $ids).')');
		$in_cost = microtime(true) - $start;
		echo 1000 * $in_cost, '<br>';
		unset($posts);
		
		$start = microtime(true);
		foreach($ids as $id){
			\fay\models\tables\Posts::model()->find($id);
			//\fay\core\Db::getInstance()->fetchRow('SELECT id,title FROM posts_0 WHERE id = '.$id);
		}
		$simple_cost = microtime(true) - $start;
		echo 1000 * $simple_cost, '<br>';
		echo '相差：', 1000 * ($simple_cost - $in_cost), 'ms';
	}
	
	public function cache(){
		//Memcache
		echo '设置缓存a，永不过期';
		dump(\F::cache()->set('a', 'b', 100, 'memcache'));
		echo '读取缓存a';
		dump(\F::cache()->get('a', 'memcache'));
		
		echo '设置缓存c，过期时间3秒';
		dump(\F::cache()->set('c', 'b', 3, 'memcache'));
		echo '获取缓存c';
		dump(\F::cache()->get('c', 'memcache'));
		
		echo '批量设置缓存d, f';
		dump(\F::cache()->mset(array(
			'd'=>'e',
			'f'=>'g',
		), 0, 'memcache'));
		echo '批量获取缓存d, f';
		dump(\F::cache()->mget(array('d', 'f'), 'memcache'));
		echo '删除缓存c';
		dump(\F::cache()->delete('c', 'memcache'));
		echo '删除缓存f';
		dump(\F::cache()->delete('f', 'memcache'));
		echo '批量获取缓存a, c, d, f, g';
		dump(\F::cache()->mget(array('a', 'c', 'd', 'f', 'g'), 'memcache'));
		
// 		echo '清空缓存';
// 		dump(\F::cache()->flush(null, 'memcache'));
// 		echo '批量获取缓存a, c, d, f, g';
// 		dump(\F::cache()->mget(array('a', 'c', 'd', 'f', 'g'), 'memcache'));
	}
	
	/**
	 * 随即更新多条数据
	 */
	public function update(){
		$rand = array();
		for($i = 0; $i < 1000; $i++){
			$rand[] = mt_rand(1, 600000);
		}
		
		$start_time = microtime(true);
		foreach($rand as $r){
			Posts::model()->update(array(
				'last_modified_time'=>time(),
			), $r);
		}
		
		dump($rand);
		echo microtime(true) - $start_time;
		dump(Posts::model()->db->getSqlLogs());
	}
	
	/**
	 * 批量执行SQL测试
	 */
	public function db(){
		$db = Db::getInstance();
		$sql = '-- 页面
INSERT INTO `faycms_pages` (title, alias) VALUES (\'关于我们\', \'about\');

--　基础分类
INSERT INTO `faycms_categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES (\'1000\', \'师资力量\', \'teacher\', \'1\', \'1\', \'1\');
INSERT INTO `faycms_categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES (\'1001\', \'学生作品\', \'works\', \'1\', \'1\', \'1\');';
		
// 		$sql = ';UPDATE faycms_categories SET alias = \'about\' WHERE id = 1';
		$db->exec($sql, true);
		
		dump($db->getSqlLogs());
	}
}