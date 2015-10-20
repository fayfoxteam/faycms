<?php
namespace fay\models\shop;

use fay\core\Model;

class Order extends Model{
	/**
	 * @return Order
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
}