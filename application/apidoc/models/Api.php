<?php
namespace apidoc\models;

use fay\core\Model;
use apidoc\models\tables\Apis;
use fay\models\Category;
use apidoc\models\tables\Inputs;
use fay\core\Sql;

class Api extends Model{
	/**
	 * @return Api
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function get($id){
		$api = Apis::model()->find($id);
		if(!$api){
			return false;
		}
		
		$sql = new Sql();
		$return = array(
			'api'=>$api,
			'category'=>Category::model()->get($api['cat_id'], 'alias'),
			'inputs'=>Inputs::model()->fetchAll('api_id = '.$id, '*', 'required DESC, name ASC'),
			'outputs'=>$sql->from(array('o'=>'apidoc_outputs'), array('name', 'sample', 'description', 'model_id'))
				->joinLeft(array('ob'=>'apidoc_models'), 'o.model_id = ob.id', array('name AS model_name'))
				->where('o.api_id = ' . $id)
				->order('o.sort, o.name')
				->fetchAll(),
		);
		return $return;
	}
}