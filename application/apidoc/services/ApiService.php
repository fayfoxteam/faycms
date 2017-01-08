<?php
namespace apidoc\services;

use apidoc\models\tables\ApisTable;
use fay\core\Service;
use fay\services\CategoryService;
use apidoc\models\tables\InputsTable;
use fay\core\Sql;

class ApiService extends Service{
	/**
	 * @param string $class_name
	 * @return ApiService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	public function get($id){
		$api = ApisTable::model()->find($id);
		if(!$api){
			return false;
		}
		
		$sql = new Sql();
		$return = array(
			'api'=>$api,
			'category'=>CategoryService::service()->get($api['cat_id'], 'alias'),
			'inputs'=>InputsTable::model()->fetchAll('api_id = '.$id, '*', 'required DESC, name ASC'),
			'outputs'=>$sql->from(array('o'=>'apidoc_outputs'), array('name', 'sample', 'description', 'model_id', 'is_array'))
				->joinLeft(array('ob'=>'apidoc_models'), 'o.model_id = ob.id', array('name AS model_name'))
				->where('o.api_id = ' . $id)
				->order('o.sort, o.name')
				->fetchAll(),
		);
		return $return;
	}
}