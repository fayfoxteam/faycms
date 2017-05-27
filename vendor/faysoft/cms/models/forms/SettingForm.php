<?php
namespace cms\models\forms;

use fay\core\Loader;
use fay\core\Model;

class SettingForm extends Model{
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array('page_size', 'int', array('min'=>1, 'max'=>999)),
            array('_key', 'required'),
        );
    }

    public function labels(){
        return array(
            'page_size'=>'页面大小',
        );
    }

    public function filters(){
        return array(
            'page_size'=>'intval',
        );
    }
}