<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 商户表
 * 
 * @property int $id Id
 * @property string $name 商户名称
 * @property string $mobile 商户电话
 * @property string $email 邮箱
 * @property string $passwd Passwd
 * @property string $encrypt Encrypt
 * @property int $status 账号状态:0关闭,1开启,2过期
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property int $created_ip Created Ip
 * @property int $is_insider 是否内部人员
 */
class GgMerchantTable extends Table{
    protected $_name = 'gg_merchant';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>32)),
            array(array('passwd'), 'string', array('max'=>37)),
            array(array('encrypt'), 'string', array('max'=>8)),
            array(array('is_insider'), 'range', array('range'=>array(0, 1))),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'商户名称',
            'mobile'=>'商户电话',
            'email'=>'邮箱',
            'passwd'=>'Passwd',
            'encrypt'=>'Encrypt',
            'status'=>'账号状态:0关闭,1开启,2过期',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'created_ip'=>'Created Ip',
            'is_insider'=>'是否内部人员',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'mobile'=>'trim',
            'email'=>'trim',
            'passwd'=>'trim',
            'encrypt'=>'trim',
            'status'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'created_ip'=>'intval',
            'is_insider'=>'intval',
        );
    }
}