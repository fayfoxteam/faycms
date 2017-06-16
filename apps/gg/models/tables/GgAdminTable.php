<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 后台管理员账号表
 * 
 * @property int $id Id
 * @property string $name 登陆名称
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property int $website_id 网站ID
 * @property int $role_id 角色ID
 * @property string $passwd 登陆密码
 * @property string $encrypt 加密密码
 * @property string $real_name 真实名称
 * @property string $mobile 手机号码
 * @property string $email 邮箱
 * @property string $login_at 登录时间
 * @property int $login_ip 登录IP
 * @property int $status 账号状态:0未激活,1开启,2关闭,3异常
 * @property int $is_super 是否超级管理员
 * @property string $updated_at 更新时间
 * @property int $updated_ip Updated Ip
 * @property string $created_at 创建时间
 * @property int $created_ip Created Ip
 * @property string $deleted_at 删除时间
 */
class GgAdminTable extends Table{
    protected $_name = 'gg_admin';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('login_ip', 'updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('merchant_id', 'website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('role_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name', 'real_name'), 'string', array('max'=>32)),
            array(array('passwd'), 'string', array('max'=>37)),
            array(array('encrypt'), 'string', array('max'=>8)),
            array(array('is_super'), 'range', array('range'=>array(0, 1))),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'登陆名称',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'website_id'=>'网站ID',
            'role_id'=>'角色ID',
            'passwd'=>'登陆密码',
            'encrypt'=>'加密密码',
            'real_name'=>'真实名称',
            'mobile'=>'手机号码',
            'email'=>'邮箱',
            'login_at'=>'登录时间',
            'login_ip'=>'登录IP',
            'status'=>'账号状态:0未激活,1开启,2关闭,3异常',
            'is_super'=>'是否超级管理员',
            'updated_at'=>'更新时间',
            'updated_ip'=>'Updated Ip',
            'created_at'=>'创建时间',
            'created_ip'=>'Created Ip',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'merchant_id'=>'intval',
            'website_id'=>'intval',
            'role_id'=>'intval',
            'passwd'=>'trim',
            'encrypt'=>'trim',
            'real_name'=>'trim',
            'mobile'=>'trim',
            'email'=>'trim',
            'login_at'=>'',
            'login_ip'=>'intval',
            'status'=>'intval',
            'is_super'=>'intval',
            'updated_at'=>'',
            'updated_ip'=>'intval',
            'created_at'=>'',
            'created_ip'=>'intval',
            'deleted_at'=>'',
        );
    }
}