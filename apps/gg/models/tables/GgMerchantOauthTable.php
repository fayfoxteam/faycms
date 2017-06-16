<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg merchant oauth table model
 * 
 * @property int $id Id
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property int $type 第三方类型
 * @property string $key Key
 * @property string $refresh_token Refresh Token
 * @property int $logined_ip Logined Ip
 * @property string $logined_at 登录时间
 * @property int $created_ip Created Ip
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgMerchantOauthTable extends Table{
    protected $_name = 'gg_merchant_oauth';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('logined_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('merchant_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('type'), 'int', array('min'=>0, 'max'=>255)),
            array(array('key', 'refresh_token'), 'string', array('max'=>32)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'type'=>'第三方类型',
            'key'=>'Key',
            'refresh_token'=>'Refresh Token',
            'logined_ip'=>'Logined Ip',
            'logined_at'=>'登录时间',
            'created_ip'=>'Created Ip',
            'created_at'=>'创建时间',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'merchant_id'=>'intval',
            'type'=>'intval',
            'key'=>'trim',
            'refresh_token'=>'trim',
            'logined_ip'=>'intval',
            'logined_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}