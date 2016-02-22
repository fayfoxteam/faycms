<?php
namespace fay\core;

class Validator{
	public static $map = array(
		'email'=>'fay\validators\Email',
		'string'=>'fay\validators\StringValidator',
		'required'=>'fay\validators\Required',
		'int'=>'fay\validators\IntValidator',
		'float'=>'fay\validators\FloatValidator',
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
	 * 错误码
	 */
	public $code = 'invalid-parameter:{$field}:{$rule}';
	
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
	public $skip_all_on_error = true;
	
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
	 * 验证规则
	 * 会根据self::$map自动设置（同一个验证器可以有不同的name）
	 */
	public $_rule;
	
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
			
			$validate = $this->createValidator($r[1], isset($r[2]) ? $r[2] : array());
			foreach($r[0] as $field){
				if(!$validate)continue;//无法识别的验证器直接跳过
				$validate->_field = $field;
				$validate->_object = $this;
				$value = isset($data[$field]) ? $data[$field] : null;
				
				if($r[1] != 'required' && $validate->isSkip($field, $value)){
					/*
					 * required验证器肯定更不能跳过
					 * 该字段已经存在错误信息，跳过验证
					 */
					continue;
				}
				$validate->validate($value, $field);
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
		$instance->_rule = $name;
		//有些验证器支持传入数组，这时候在外面无法判断是否为空
		$instance->skip_on_empty = $this->skip_on_empty;
		return $instance;
	}
	
	/**
	 * 供子类调用的设置错误信息的方法
	 * @param string $message 一个验证器可能有多种错误描述，所以不能直接通过$this->message获取
	 * @param string $code 同上
	 * @param array $params
	 */
	public function addError($message = null, $code = null, $params = array()){
		//当直接实例化验证器时，$this->object为null
		if($this->_object){
			$this->_object->_addError($this->_field, $this->_rule, $message === null ? $this->message : $message, $code === null ? $this->code : $code, $params);
		}
		
		//返回错误描述，直接调用Validator时或许会有用
		return $message;
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
	private function _addError($field, $rule, $message, $code = '', $params = array()){
		$params['attribute'] = isset($this->labels[$field]) ? $this->labels[$field] : $field;
		$params['field'] = $field;
		$params['rule'] = $rule;
		$search = array();
		$replace = array();
		foreach($params as $k=>$p){
			$search[] = "{\$$k}";
			$replace[] = $p;
		}
		$message = str_replace($search, $replace, $message);
		$code = str_replace($search, $replace, $code);
		$this->errors[] = array(
			'field'=>$field,
			'rule'=>$rule,
			'message'=>$message,
			'code'=>$code,
		);
	}
	
	/**
	 * 是否跳过该字段验证
	 * @param string $field 字段名
	 * @param mixed $value 字段值
	 * @return boolean
	 */
	private function isSkip($field, $value){
		if($this->skip_on_empty && ($value === null || $value === '' || $value === array())){
			return true;
		}
		
		if($this->skip_on_error && $this->hasError($field)){
			//该字段已经存在错误信息，跳过验证
			return true;
		}
		
		return false;
	}
}