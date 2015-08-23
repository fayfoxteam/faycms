<?php
namespace fay\core\db;

class Intact{
	private $value = '';
	public function __construct($value){
		$this->value = $value;
	}
	
	public function get(){
		return $this->value;
	}
}