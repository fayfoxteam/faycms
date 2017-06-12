<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Apidoc apis table model
 * 
 * @property int $id Id
 * @property int $app_id App ID
 * @property string $title 标题
 * @property string $router 路由
 * @property string $description 描述
 * @property int $status 状态
 * @property int $http_method HTTP请求方式
 * @property int $need_login 是否需要登录
 * @property int $cat_id 分类
 * @property int $user_id 用户
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property string $since 自从
 * @property string $sample_response 响应示例
 */
class ApisTable extends Table{
    /**
     * 状态 - 开发中
     */
    const STATUS_DEVELOPING = 1;
    
    /**
     * 状态 - 测试版
     */
    const STATUS_BETA = 2;
    
    /**
     * 状态 - 稳定版
     */
    const STATUS_STABLE = 3;
    
    /**
     * 状态 - 已弃用
     */
    const STATUS_DEPRECATED = 4;
    
    /**
     * HTTP请求方式 - GET
     */
    const HTTP_METHOD_GET = 1;
    
    /**
     * HTTP请求方式 - POST
     */
    const HTTP_METHOD_POST = 2;
    
    /**
     * HTTP请求方式 - GET/POST
     */
    const HTTP_METHOD_BOTH = 3;
    
    protected $_name = 'apidoc_apis';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('id', 'app_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('title'), 'string', array('max'=>255)),
            array(array('router'), 'string', array('max'=>100)),
            array(array('since'), 'string', array('max'=>30)),
            
            array(array('title', 'router'), 'required'),
            array('status', 'range', array('range'=>array(
                self::STATUS_DEVELOPING, self::STATUS_BETA, self::STATUS_STABLE, self::STATUS_DEPRECATED
            ))),
            array('http_method', 'range', array('range'=>array(
                self::HTTP_METHOD_GET, self::HTTP_METHOD_POST, self::HTTP_METHOD_BOTH
            ))),
            array('router', 'unique', array('table'=>$this->_name, 'except'=>'id', 'ajax'=>array('apidoc/admin/api/is-router-not-exist'))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'app_id'=>'App ID',
            'title'=>'标题',
            'router'=>'路由',
            'description'=>'描述',
            'status'=>'状态',
            'http_method'=>'HTTP请求方式',
            'need_login'=>'是否需要登录',
            'cat_id'=>'分类',
            'user_id'=>'用户',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'since'=>'自从',
            'sample_response'=>'响应示例',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'app_id'=>'intval',
            'title'=>'trim',
            'router'=>'trim',
            'description'=>'',
            'status'=>'intval',
            'http_method'=>'intval',
            'need_login'=>'intval',
            'cat_id'=>'intval',
            'user_id'=>'intval',
            'since'=>'trim',
            'sample_response'=>'',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
            break;
            case 'update':
            default:
                return array(
                    'id', 'create_time', 'user_id'
                );
        }
    }
    
    /**
     * 返回状态位-状态描述数组
     */
    public static function getStatus(){
        return array(
            self::STATUS_BETA => '测试中',
            self::STATUS_DEPRECATED => '已弃用',
            self::STATUS_DEVELOPING => '开发中',
            self::STATUS_STABLE => '已上线',
        );
    }
    
    /**
     * 获取HTTP请求方式
     */
    public static function getHttpMethods(){
        return array(
            self::HTTP_METHOD_BOTH => 'GET/POST',
            self::HTTP_METHOD_GET => 'GET',
            self::HTTP_METHOD_POST => 'POST',
        );
    }
    
    public function getPublicFields(){
        return $this->getFields(array('create_time', 'update_time'));
    }
}