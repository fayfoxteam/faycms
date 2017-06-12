<?php
namespace apidoc\services;

use apidoc\models\tables\ApidocModelsTable;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\NumberHelper;

class ModelService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 根据模块ID或名称，获取模块示例
     * @param int $key 模块id或名称
     * @return string
     */
    public function getSample($key){
        if(NumberHelper::isInt($key)){
            $model = ApidocModelsTable::model()->find($key, 'sample');
        }else{
            $model = ApidocModelsTable::model()->fetchRow(array(
                'name = ?'=>$key,
            ), 'sample');
        }
        
        return $model ? $model['sample'] : '';
    }
}