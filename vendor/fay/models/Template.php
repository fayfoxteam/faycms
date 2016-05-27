<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Templates;

class Template extends Model{
	/**
	 * 算是缓存吧
	 * @var array
	 */
	public $templates = array();
	
	/**
	 * @param string $class_name
	 * @return Template
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function render($alias, $options = array()){
		if(isset($this->templates[$alias])){
			$msg = $this->templates[$alias];
		}else{
			$msg = Templates::model()->fetchRow(array(
				'alias = ?'=>$alias,
			));
			$this->templates[$alias] = $msg;
		}
		
		if($msg && $msg['enable']){
			if(!empty($options)){
				foreach ( $options as $key => $value ) {
					$msg['content'] = str_replace ( '{$' . $key . '}', $value, $msg['content'] );
				}
			}
			return $msg;
		}else{
			return false;
		}
	}
	
	public static function getType($type){
		switch($type){
			case Templates::TYPE_EMAIL:
				return '邮件';
			break;
			case Templates::TYPE_NOTIFICATION:
				return '站内信';
			break;
			case Templates::TYPE_SMS:
				return '短信';
			break;
			default:
				return '未知';
			break;
		}
	}
}