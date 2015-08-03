<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Tokens extends Table{

	protected $_name = 'tokens';

	/**
	 * @return Users
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
        return array(

        );
	}

	public function labels(){
		return array(

		);
	}

	public function filters(){
        return array(

        );
	}
}