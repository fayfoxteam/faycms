<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 后台管理员账号表
 *
 * @property int $id Id
 * @property string $name 登陆名称
 * @property int $avatar 头像
 * @property string $passwd 登陆密码
 * @property string $encrypt 加密密码
 * @property string $real_name 真实名称
 * @property string $mobile 手机号码
 * @property string $email 邮箱
 * @property string $login_time 登录时间
 * @property int $login_ip 登录IP
 * @property int $status 账号状态:0未激活,1开启,2关闭,3异常
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgManageTable extends Table{
    protected $_name = 'gg_manage';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('login_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'avatar'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name', 'real_name'), 'string', array('max'=>32)),
            array(array('passwd'), 'string', array('max'=>37)),
            array(array('encrypt'), 'string', array('max'=>8)),
            array(array('login_time'), 'datetime'),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'登陆名称',
            'avatar'=>'头像',
            'passwd'=>'登陆密码',
            'encrypt'=>'加密密码',
            'real_name'=>'真实名称',
            'mobile'=>'手机号码',
            'email'=>'邮箱',
            'login_time'=>'登录时间',
            'login_ip'=>'登录IP',
            'status'=>'账号状态:0未激活,1开启,2关闭,3异常',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'avatar'=>'intval',
            'passwd'=>'trim',
            'encrypt'=>'trim',
            'real_name'=>'trim',
            'mobile'=>'trim',
            'email'=>'trim',
            'login_time'=>'',
            'login_ip'=>'intval',
            'status'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}