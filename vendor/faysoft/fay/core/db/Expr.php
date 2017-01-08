<?php
namespace fay\core\db;

class Expr{
	protected $_expression = '';
	
	public function __construct($value){
		$this->_expression = $value;
	}
	
	public function get(){
		return $this->_expression;
	}
	
	public function __toString(){
		return $this->_expression;
	}
}