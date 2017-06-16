<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg coltd partners table model
 * 
 * @property int $id Id
 * @property string $name Name
 * @property string $address Address
 * @property string $realname Realname
 * @property string $mobile Mobile
 * @property string $email Email
 * @property int $created_ip Created Ip
 * @property string $created_at Created At
 */
class GgColtdPartnersTable extends Table{
    protected $_name = 'gg_coltd_partners';
    
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
            array(array('name', 'realname'), 'string', array('max'=>32)),
            array(array('address'), 'string', array('max'=>255)),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'Name',
            'address'=>'Address',
            'realname'=>'Realname',
            'mobile'=>'Mobile',
            'email'=>'Email',
            'created_ip'=>'Created Ip',
            'created_at'=>'Created At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'address'=>'trim',
            'realname'=>'trim',
            'mobile'=>'trim',
            'email'=>'trim',
            'created_ip'=>'intval',
            'created_at'=>'',
        );
    }
}