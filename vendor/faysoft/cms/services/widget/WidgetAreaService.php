<?php
namespace cms\services\user;

use fay\core\ErrorException;
use fay\core\Service;

class WidgetAreaService extends Service{
    /**
     * @param string $class_name
     * @return WidgetAreaService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    public function create($alias, $description = ''){
        if(!$alias){
            throw new ErrorException('小工具域别名不能为空');
        }
    }
    
    public function update(){
        
    }
    
    public function remove(){
        
    }

    /**
     * 关联小工具
     */
    public function relateWidget(){
        
    }
    
    public function getAll(){
        
    }
}