<?php
namespace fay\validators;

use fay\core\Validator;
use fay\core\Sql;

/**
 * 该验证器必须传入table, field参数
 */
class Exist extends Validator{
	public $message = '{$attribute}不存在';
	
	public $code = 'invalid-parameter:{$field}-is-not-exist';
	
	public $table;
	
	public $field;
	
	public function validate($value){
		if($value == 0){
			//在此验证器中，0也被视为空
			if($this->skip_on_empty){
				return true;
			}else{
				return $this->addError($this->message, $this->code);
			}
		}
		$field = $this->field ? $this->field : $this->_field;
		
		$sql = new Sql();
		if($sql->from($this->table, $field)
			->where(array(
				"`{$field}` = ?"=>$value,
			))
			->fetchRow()
		){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}