<?php
namespace fayoauth\services;

use fay\core\Service;
use fay\helpers\NumberHelper;
use fayoauth\models\tables\OauthAppsTable;

/**
 * 用于获取第三方app配置信息
 */
class OauthAppService extends Service{
    /**
     * @param string $class_name
     * @return OauthAppService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 根据第三方App Id获取本地oauth_apps表自递增ID
     * @param string $app_id
     * @return string
     */
    public function getIdByAppId($app_id){
        $oauth_app = OauthAppsTable::model()->fetchRow(array(
            'app_id = ?'=>$app_id,
        ), 'id');
        
        return $oauth_app ? $oauth_app['id'] : '0';
    }
    
    /**
     * 获取应用
     *  - 若$app值是数字，则根据id获取
     *  - 若$app值是字符串，则根据alias获取
     * @param int|string $app
     * @param string|array $fields 字段
     * @return array|bool
     */
    public function get($app, $fields = '*'){
        if(NumberHelper::isInt($app)){
            return $this->getById($app, $fields);
        }else{
            return $this->getByAlias($app, $fields);
        }
    }
    
    /**
     * 根据ID获取
     * @param int $id
     * @param string|array $fields 字段
     * @return array|bool
     */
    public function getById($id, $fields = '*'){
        return OauthAppsTable::model()->find($id, $fields);
    }
    
    /**
     * 根据别名获取
     * @param string $alias
     * @param string|array $fields 字段
     * @return array|bool
     */
    public function getByAlias($alias, $fields = '*'){
        return OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$alias
        ), $fields);
    }
    
    /**
     * 新增应用
     * @param string $app_id
     * @param string $app_secret
     * @param array $extra
     * @return int|null
     * @throws OAuthException
     */
    public function create($app_id, $app_secret, $extra){
        if(!empty($extra['alias']) && OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$extra['alias']
        ))){
            throw new OAuthException('指定别名已存在');
        }
        
        if(!$app_id){
            throw new OAuthException('AppID不能为空');
        }
        if(!$app_secret){
            throw new OAuthException('AppSecret不能为空');
        }
        
        return OauthAppsTable::model()->insert(array(
            'app_id'=>$app_id,
            'app_secret'=>$app_secret,
            'create_time'=>\F::app()->current_time,
            'update_time'=>\F::app()->current_time,
        ) + $extra, true);
    }
    
    /**
     * 更新应用信息
     * @param int $id
     * @param array $app
     * @return int
     */
    public function update($id, $app){
        $app['update_time'] = \F::app()->current_time;
        
        return OauthAppsTable::model()->update($app, $id, true);
    }
    
    /**
     * 删除应用
     * @param int|string $app 支持id或alias
     * @return int|null
     * @throws OAuthException
     */
    public function delete($app){
        $row = $this->get($app, 'id,delete_time');
        if(!$row){
            throw new OAuthException('指定应用不存在');
        }
        if($row['delete_time'] != 0){
            throw new OAuthException('指定应用已删除');
        }
        
        return OauthAppsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $row['id']);
    }
    
    /**
     * @param int|string $app 支持id或alias
     * @return int|null
     * @throws OAuthException
     */
    public function undelete($app){
        $row = $this->get($app, 'id,delete_time');
        if(!$row){
            throw new OAuthException('指定应用不存在');
        }
        if($row['delete_time'] == 0){
            throw new OAuthException('指定应用未删除');
        }
        
        return OauthAppsTable::model()->update(array(
            'delete_time'=>0,
        ), $row['id']);
    }
}