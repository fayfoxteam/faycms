<?php
namespace siwi\models;

use fay\core\Loader;
use fay\core\Model;
use cms\services\CategoryService;

class Post extends Model{
    public $cats = array();
    
    /**
     * @return Post
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function getType($cat_id){
        $cat = CategoryService::service()->get($cat_id, 'left_value,right_value');
        if(empty($this->cats)){
            $this->cats = CategoryService::service()->getNextLevel('_system_post', 'alias,left_value,right_value');
        }
        
        foreach($this->cats as $c){
            if($cat['left_value'] > $c['left_value'] && $cat['right_value'] < $c['right_value'])
                return ltrim($c['alias'], '_');
        }
    }
}