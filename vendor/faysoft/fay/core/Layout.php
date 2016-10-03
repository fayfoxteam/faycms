<?php
namespace fay\core;

class Layout{
	/**
	 * 用于layout的数据
	 * @var array
	 */
	private $_layout_data = array();
	
	/**
	 * 向模板传递一堆参数
	 * @param array $options
	 */
	public function assign($options){
		$this->_layout_data = array_merge($this->_layout_data, $options);
	}
	
	public function getLayoutData(){
		return $this->_layout_data;
	}
	
	public function __set($key, $value){
		$this->_layout_data[$key] = $value;
	}
	
	public function __get($key){
		return $this->_layout_data[$key];
	}
}