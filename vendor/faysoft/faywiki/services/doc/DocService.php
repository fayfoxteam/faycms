<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;

class DocService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    public function create(){
        
    }
    
    public function update(){
        
    }
    
    public function delete(){
        
    }
    
    public function get($doc_id, $fields = '*'){
        
    }
    
    public function mget($doc_ids, $fields = '*'){
        
    }
    
    public static function isDocIdExist($doc_id){
        
    }
}