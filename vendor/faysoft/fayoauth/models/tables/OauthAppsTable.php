<?php
namespace fayoauth\models\tables;

use fay\core\db\Table;

/**
 * 第三方登录方式
 * 
 * @property int $id Id
 * @property string $alias 别名
 * @property string $name 名称
 * @property string $description 描述
 * @property string $code 登录方式编码
 * @property string $app_id 第三方应用ID
 * @property string $app_secret App Secret
 * @property int $enabled 是否启用
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $delete_time 删除时间
 */
class OauthAppsTable extends Table{
    /**
     * @var array 登录方式编码
     */
    public static $codes = array(
        'weixin'=>'微信登录'
    );
    
    protected $_name = 'oauth_apps';
    
    /**
     * @param string $class_name
     * @return OauthAppsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>30)),
            array(array('description'), 'string', array('max'=>100)),
            array(array('code'), 'string', array('max'=>20)),
            array(array('alias', 'app_id', 'app_secret'), 'string', array('max'=>50)),
            
            array(array('enabled'), 'range', array('range'=>array(0, 1))),
            array('alias', 'unique', array('table'=>'oauth_apps', 'except'=>'id', 'ajax'=>array('fayoauth/admin/app/is-alias-not-exist'))),
            array(array('alias'), 'string', array('format'=>'alias')),
            array(array('name', 'app_id', 'app_secret'), 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'alias'=>'别名',
            'name'=>'名称',
            'description'=>'描述',
            'code'=>'登录方式编码',
            'app_id'=>'第三方应用ID',
            'app_secret'=>'App Secret',
            'enabled'=>'是否启用',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'delete_time'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'alias'=>'trim',
            'name'=>'trim',
            'description'=>'trim',
            'code'=>'trim',
            'app_id'=>'trim',
            'app_secret'=>'trim',
            'enabled'=>'intval',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
                return array(
                    'id', 'create_time', 'delete_time'
                );
                break;
            default:
                return array();
        }
    }
}