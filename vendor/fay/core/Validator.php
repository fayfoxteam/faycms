<?php
namespace fay\core;

class Validator{
	public static $map = array(
		'email'=>'fay\validators\Email',
		'string'=>'fay\validators\String',
		'required'=>'fay\validators\Required',
		'int'=>'fay\validators\Int',
		'float'=>'fay\validators\Float',
		'mobile'=>'fay\validators\Mobile',
		'url'=>'fay\validators\Url',
		'chinese'=>'fay\validators\Chinese',
		'zh'=>'fay\validators\Chinese',
		'exist'=>'fay\validators\Exist',
		'unique'=>'fay\validators\Unique',
		'datetime'=>'fay\validators\Datetime',
		'range'=>'fay\validators\Range',
		'compare'=>'fay\validators\Compare',
	);
	
	/**
	 * 错误描述
	 */
	public $message = '{$attribute}字段不符合要求';
	
	/**
	 * 字段标签
	 */
	public $labels = array();
	
	/**
	 * 错误信息
	 */
	public $errors = array();
	
	/**
	 * 当一个字段已经存在错误信息，则跳过该字段的其它验证
	 * @var boolean
	 */
	public $skip_on_error = true;
	
	/**
	 * 当一个字段存在错误信息，则跳过所有验证
	 * @var boolean
	 */
	public $skip_all_on_error = false;
	
	/**
	 * 当一个字段为null时，跳过验证
	 * @var boolean
	 */
	public $skip_on_empty = true;
	
	/**
	 * 当前验证字段<br>
	 * 所有在本类中被实例化的验证器实例都将包含此变量
	 */
	public $_field;
	
	/**
	 * Validator实例<br>
	 * 所有在本类中被实例化的验证器实例都将包含此变量
	 */
	public $_object;
	
	/**
	 * 初始化
	 */
	public function init($params){
		foreach($params as $k=>$p){
			$this->$k = $p;
		}
	}
	
	/**
	 * 执行验证
	 */
	public function check($rules, $labels = array(), $source = 'request'){
		if(is_array($source)){
			$data = $source;
		}else if($source == 'post'){
			$data = \F::app()->input->post();
		}else if($source == 'get'){
			$data = \F::app()->input->get();
		}else{
			$data = \F::app()->input->request();
		}
		
		$this->setLables($labels);
		
		foreach($rules as $r){
			if($this->skip_all_on_error && $this->errors){
				//有错误信息就结束验证
				break;
			}
			
			if(!is_array($r[0])){
				$r[0] = array($r[0]);
			}
			
			foreach($r[0] as $field){
				$validate = $this->createValidator($r[1], isset($r[2]) ? $r[2] : array());
				if(!$validate)continue;//无法识别的验证器直接跳过
				$validate->_field = $field;
				$validate->_object = $this;
				$value = isset($data[$field]) ? $data[$field] : null;
				
				if(is_array($value)){
					foreach($value as $v){
						if($validate->isSkip($field, $v)){
							//该字段已经存在错误信息，跳过验证
							continue;
						}
						$result = $validate->validate($v, $field);
						if($result !== true && is_string($result)){
							$this->_addError($field, $r[1], $result);
							break;
						}
					}
				}else{
					if($validate->isSkip($field, $value)){
						//该字段已经存在错误信息，跳过验证
						continue;
					}
					$result = $validate->validate($value, $field);
					if($result !== true && is_string($result)){
						$this->_addError($field, $r[1], $result);
					}
				}
			}
		}
		
		if($this->errors){
			return $this->errors;
		}else{
			return true;
		}
	}
	
	/**
	 * 获取一个验证器实例
	 * @param string $name 验证器名称
	 * @param array $params 传入参数
	 * @return Validator
	 */
	public function createValidator($name, $params = array()){
		if(isset(self::$map[$name])){
			$instance = new self::$map[$name];
		}else{
			return false;
		}
		$instance->init($params);
		return $instance;
	}
	
	/**
	 * 供子类调用的设置错误信息的方法
	 * @param string $field
	 * @param string $rule
	 * @param string $message
	 * @param array $params
	 */
	public function addError($field, $rule, $message, $params = array()){
		//当直接实例化验证器时，该属性为null
		if($this->_object){
			$this->_object->_addError($field, $rule, $message, $params);
		}
		
		return false;
	}
	
	/**
	 * 判断该字段是否已存在错误信息
	 * @param string $field 字段名称
	 * @return boolean
	 */
	public function hasError($field){
		foreach($this->errors as $e){
			if($e[0] == $field){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 验证一个字段<br>
	 * 所有验证器需要实现此方法
	 * @param mixed $value 字段值
	 */
	public function validate($value){
		return true;
	}
	
	/**
	 * 设置Labels
	 * @param array $labels
	 */
	public function setLables($labels){
		$this->labels = array_merge($this->labels, $labels);
	}
	
	/**
	 * 注册一个验证器
	 * @param string $name
	 * @param string $class_name 带命名空间的类名
	 */
	public function registerValidator($name, $class_name){
		self::$map[$name] = $class_name;
	}
	
	/**
	 * 添加错误信息
	 * @param string $field 出错字段
	 * @param string $rule 规则
	 * @param string $message 错误描述
	 */
	private function _addError($field, $rule, $message, $params = array()){
		$params['attribute'] = isset($this->labels[$field]) ? $this->labels[$field] : $field;
		$search= array();
		$replace = array();
		foreach($params as $k=>$p){
			$search[] = "{\$$k}";
			$replace[] = $p;
		}
		
		$this->errors[] = array(
			$field, $rule, str_replace($search, $replace, $message),
		);
		
		return false;
	}
	
	/**
	 * 是否跳过该字段验证
	 * @param string $field 字段名
	 * @param mixed $value 字段值
	 * @return boolean
	 */
	private function isSkip($field, $value){
		if($this->skip_on_empty && ($value === null || $value === '')){
			return true;
		}
		
		if($this->skip_on_error && $this->hasError($field)){
			//该字段已经存在错误信息，跳过验证
			return true;
		}
		
		return false;
	}
}