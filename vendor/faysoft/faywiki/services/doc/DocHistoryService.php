<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;

class DocHistoryService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    public function create($doc_id){
        
    }
    
    public function get($doc_id, $fields = '*'){
        
    }
    
    public function getDocHistories($doc_id, $fields = '*', $limit = 10, $last_id = 0){
        
    }
}