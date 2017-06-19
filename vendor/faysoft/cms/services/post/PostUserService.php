<?php
namespace cms\services\post;

use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;

class PostUserService extends Service{
    /**
     * 默认返回用户字段
     */
    public static $default_fields = array(
        'user'=>array(
            'id', 'nickname', 'avatar',
        )
    );
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 将user信息装配到$posts中
     * @param array $posts 包含文章信息的三维数组，必须包含$posts.post.user_id字段
     * @param null|string $fields
     * @throws PostErrorException
     */
    public function assemble(&$posts, $fields = null){
        if(empty($fields)){
            //若传入$fields为空，则返回默认字段
            $fields = self::$default_fields;
        }
        
        //获取所有用户ID
        $user_ids = array();
        foreach($posts as $p){
            if(isset($p['post']['user_id'])){
                $user_ids[] = $p['post']['user_id'];
            }else{
                throw new PostErrorException(__CLASS__.'::'.__METHOD__.'()方法$posts参数中，必须包含user_id项');
            }
        }
        
        $user_map = UserService::service()->mget($user_ids, $fields);
        
        foreach($posts as $k => $p){
            $p['user'] = $user_map[$p['post']['user_id']];
            
            $posts[$k] = $p;
        }
    }
}