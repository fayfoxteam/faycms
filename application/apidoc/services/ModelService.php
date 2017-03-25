<?php
namespace apidoc\services;

use apidoc\models\tables\ModelsTable;
use fay\core\Service;
use fay\helpers\NumberHelper;

class ModelService extends Service{
	/**
	 * @param string $class_name
	 * @return ModelService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 根据模块ID或名称，获取模块示例
	 * @param int $key 模块id或名称
	 * @return string
	 */
	public function getSample($key){
		if(NumberHelper::isInt($key)){
			$model = ModelsTable::model()->find($key, 'sample');
		}else{
			$model = ModelsTable::model()->fetchRow(array(
				'name = ?'=>$key,
			), 'sample');
		}
		
		return $model ? $model['sample'] : '';
	}
}