<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 第三方登录信息
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $oauth_app_id oauth_apps表ID
 * @property string $open_id 第三方应用对外ID
 * @property string $unionid Union ID
 * @property int $create_time 创建时间
 * @property string $access_token Access Token
 * @property int $expires_in access_token过期时间戳
 * @property string $refresh_token Refresh Token
 */
class UserConnectsTable extends Table{
    protected $_name = 'user_connects';
    
    /**
     * @param string $class_name
     * @return UserConnectsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id', 'user_id', 'expires_in'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('oauth_app_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('open_id', 'unionid'), 'string', array('max'=>50)),
            array(array('access_token', 'refresh_token'), 'string', array('max'=>255)),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'oauth_app_id'=>'oauth_apps表ID',
            'open_id'=>'第三方应用对外ID',
            'unionid'=>'Union ID',
            'create_time'=>'创建时间',
            'access_token'=>'Access Token',
            'expires_in'=>'access_token过期时间戳',
            'refresh_token'=>'Refresh Token',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'oauth_app_id'=>'intval',
            'open_id'=>'trim',
            'unionid'=>'trim',
            'access_token'=>'trim',
            'expires_in'=>'intval',
            'refresh_token'=>'trim',
        );
    }
}