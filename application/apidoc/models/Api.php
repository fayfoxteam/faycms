<?php
namespace apidoc\models;

use fay\core\Model;
use apidoc\models\tables\Apis;
use fay\models\Category;
use apidoc\models\tables\Inputs;
use apidoc\models\tables\Outputs;
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
		$outputs = $sql->from(array('ao'=>'apidoc_apis_outputs'), '')
			->joinLeft(array('o'=>'apidoc_outputs'), 'ao.output_id = o.id', Outputs::model()->getPublicFields())
			->where('ao.api_id = ' . $id)
			->fetchAll();
		$return = array(
			'api'=>$api,
			'category'=>Category::model()->get($api['cat_id'], 'alias'),
			'inputs'=>Inputs::model()->fetchAll('api_id = '.$id, '*', 'required DESC, name ASC'),
			'outputs'=>$outputs,
		);
		return $return;
	}
}