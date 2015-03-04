<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Validator;
use fay\helpers\Html;

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
				//$this->flash->set(pr($valid, true, true));
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
// 				'class'=>'color-red',
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
// 							'class'=>'color-red',
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
}