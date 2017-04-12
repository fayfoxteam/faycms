<?php
namespace fayoauth\services;

use fay\core\Service;
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
}