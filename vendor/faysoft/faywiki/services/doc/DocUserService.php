<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;
use cms\services\user\UserService;

class DocUserService extends Service{
    /**
     * 默认返回用户字段
     */
    public static $default_fields = array(
        'user'=>array(
            'fields'=>array(
                'id', 'nickname', 'avatar',
            )
        )
    );
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 将user信息装配到$docs中
     * @param array $docs 包含文档信息的三维数组，必须包含$docs.doc.user_id字段
     * @param null|string $fields
     * @throws DocErrorException
     */
    public function assemble(&$docs, $fields = null){
        if(empty($fields)){
            //若传入$fields为空，则返回默认字段
            $fields = self::$default_fields;
        }
        
        //获取所有用户ID
        $user_ids = array();
        foreach($docs as $doc){
            if(isset($doc['doc']['user_id'])){
                $user_ids[] = $doc['doc']['user_id'];
            }else{
                throw new DocErrorException(__CLASS__.'::'.__METHOD__.'()方法$docs参数中，必须包含user_id项');
            }
        }
        
        $user_map = UserService::service()->mget($user_ids, $fields);
        
        foreach($docs as $k => $doc){
            $doc['user'] = $user_map[$doc['doc']['user_id']];
            
            $docs[$k] = $doc;
        }
    }
}