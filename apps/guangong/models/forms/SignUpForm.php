<?php
namespace guangong\models\forms;

use fay\core\Loader;
use fay\core\Model;

class SignUpForm extends Model{
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('mobile', 'birthday', 'state', 'city', 'realname'), 'required'),
            array('mobile', 'mobile'),
            //array('captcha', 'captcha'),
        );
    }
    
    public function labels(){
        return array(
            'mobile'=>'识别码',
            'birthday'=>'出生期',
            'state'=>'省',
            'city'=>'市',
            'district'=>'县',
            'realname'=>'姓名',
            //'captcha'=>'验证码',
        );
    }
    
    public function filters(){
        return array(
            'name'=>'trim',
            'email'=>'trim',
            'subject'=>'trim',
            'message'=>'trim',
            'realname'=>'trim',
        );
    }
}