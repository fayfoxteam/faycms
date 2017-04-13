<?php
namespace fayoauth\services;

use fay\core\ErrorException;
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
     * 新增app
     * @param string $app_id
     * @param string $app_secret
     * @param array $extra
     * @return int|null
     * @throws ErrorException
     */
    public function create($app_id, $app_secret, $extra){
        if(!empty($extra['alias']) && OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$extra['alias']
        ))){
            throw new ErrorException('指定别名已存在');
        }
        
        if(!$app_id){
            throw new ErrorException('AppID不能为空');
        }
        if(!$app_secret){
            throw new ErrorException('AppSecret不能为空');
        }
        
        return OauthAppsTable::model()->insert(array(
            'app_id'=>$app_id,
            'app_secret'=>$app_secret,
            'create_time'=>\F::app()->current_time,
            'update_time'=>\F::app()->current_time,
        ) + $extra, true);
    }
    
    /**
     * 更新app信息
     * @param int $id
     * @param array $app
     * @return int
     */
    public function update($id, $app){
        $app['update_time'] = \F::app()->current_time;
        
        return OauthAppsTable::model()->update($app, $id, true);
    }
    
    /**
     * 获取APP
     *  - 若$app值是数字，则根据id获取
     *  - 若$app值是字符串，则根据alias获取
     * @param int|string $app
     * @return array|bool
     */
    public function get($app){
        if(NumberHelper::isInt($app)){
            return $this->getById($app);
        }else{
            return $this->getByAlias($app);
        }
    }
    
    /**
     * 根据ID获取
     * @param int $id
     * @return array|bool
     */
    public function getById($id){
        return OauthAppsTable::model()->find($id);
    }
    
    /**
     * 根据别名获取
     * @param string $alias
     * @return array|bool
     */
    public function getByAlias($alias){
        return OauthAppsTable::model()->fetchRow(array(
            'alias = ?'=>$alias
        ));
    }
}