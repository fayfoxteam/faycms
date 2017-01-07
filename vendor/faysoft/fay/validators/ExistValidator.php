<?php
namespace fay\validators;

use fay\core\Validator;
use fay\core\Sql;
use fay\core\ErrorException;

/**
 * 该验证器必须传入table, field参数
 */
class ExistValidator extends Validator{
	public $message = '{$attribute}不存在';
	
	public $code = 'invalid-parameter:{$field}-is-not-exist';
	
	/**
	 * 表名（必填）
	 */
	public $table;
	
	/**
	 * 字段名（选填，不填则默认为传入参数名）
	 */
	public $field;
	
	/**
	 * 附加条件
	 */
	public $conditions = array();
	
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
		
		if(!$this->table){
			throw new ErrorException('fay\validators\Exist验证器必须指定table参数');
		}
		
		$sql = new Sql();
		if($sql->from($this->table, $field)
			->where(array(
				"`{$field}` = ?"=>$value,
			) + $this->conditions)
			->fetchRow()
		){
			return true;
		}else{
			return $this->addError($this->message, $this->code);
		}
	}
}