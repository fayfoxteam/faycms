<?php
namespace guangong\models\forms;

use fay\core\Loader;
use fay\core\Model;

class CreateGroupForm extends Model{
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('name', 'captcha', 'count'), 'required'),
            array('captcha', 'captcha'),
            array('name', 'chinese'),
            array('count', 'int', array('min'=>2, 'max'=>10))
        );
    }
    
    public function labels(){
        return array(
            'name'=>'称谓',
            'mobile'=>'识别码',
            'captcha'=>'验证码',
            'count'=>'结义人数',
        );
    }
    
    public function filters(){
        return array(
            'name'=>'trim',
            'mobile'=>'trim',
            'count'=>'intval',
        );
    }
}