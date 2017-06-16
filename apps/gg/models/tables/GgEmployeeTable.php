<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 员工表
 *
 * @property int $id Id
 * @property string $name 姓名
 * @property int $website_id 网站ID
 * @property int $avatar 头像
 * @property string $mobile 手机号码
 * @property string $email 邮箱
 * @property string $intro 员工简介
 * @property string $address 详细地址
 * @property int $status 状态:0离职,1在职
 * @property string $updated_at 更新时间
 * @property int $updated_ip Updated Ip
 * @property string $created_at 创建时间
 * @property int $created_ip Created Ip
 * @property string $deleted_at 删除时间
 */
class GgEmployeeTable extends Table{
    protected $_name = 'gg_employee';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'avatar'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>32)),
            array(array('address'), 'string', array('max'=>100)),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'姓名',
            'website_id'=>'网站ID',
            'avatar'=>'头像',
            'mobile'=>'手机号码',
            'email'=>'邮箱',
            'intro'=>'员工简介',
            'address'=>'详细地址',
            'status'=>'状态:0离职,1在职',
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
            'website_id'=>'intval',
            'avatar'=>'intval',
            'mobile'=>'trim',
            'email'=>'trim',
            'intro'=>'',
            'address'=>'trim',
            'status'=>'intval',
            'updated_at'=>'',
            'updated_ip'=>'intval',
            'created_at'=>'',
            'created_ip'=>'intval',
            'deleted_at'=>'',
        );
    }
}