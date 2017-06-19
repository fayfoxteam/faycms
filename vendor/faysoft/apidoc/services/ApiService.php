<?php
namespace apidoc\services;

use apidoc\models\tables\ApidocApiErrorCodesTable;
use apidoc\models\tables\ApidocApisTable;
use apidoc\models\tables\ApidocInputsTable;
use cms\services\CategoryService;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;

class ApiService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    public function get($id){
        $api = ApidocApisTable::model()->find($id);
        if(!$api){
            return false;
        }
        
        $sql = new Sql();
        $return = array(
            'api'=>$api,
            'category'=>CategoryService::service()->get($api['cat_id'], 'alias'),
            'inputs'=>ApidocInputsTable::model()->fetchAll('api_id = '.$api['id'], '*', 'required DESC, name ASC'),
            'outputs'=>$sql->from(array('o'=>'apidoc_outputs'), array('name', 'sample', 'description', 'model_id', 'is_array'))
                ->joinLeft(array('ob'=>'apidoc_models'), 'o.model_id = ob.id', array('name AS model_name'))
                ->where('o.api_id = ' . $api['id'])
                ->order('o.sort, o.name')
                ->fetchAll(),
            'error_codes'=>ApidocApiErrorCodesTable::model()->fetchAll('api_id = '. $api['id'], '*', 'code'),
        );
        return $return;
    }
}