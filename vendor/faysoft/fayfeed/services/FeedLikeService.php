<?php
namespace fayfeed\services;

use cms\services\user\UserService;
use fay\core\Exception;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fayfeed\models\tables\FeedLikesTable;
use fayfeed\models\tables\FeedMetaTable;

class FeedLikeService extends Service{
    /**
     * 动态被点赞后事件
     */
    const EVENT_LIKE = 'after_feed_like';
    
    /**
     * 动态被取消点赞后事件
     */
    const EVENT_UNLIKE = 'after_feed_unlike';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 给动态点赞
     * @param int $feed_id 动态ID
     * @param string $trackid
     * @param int $user_id 用户ID，默认为当前登录用户
     * @param bool|int $sockpuppet 马甲信息
     * @throws Exception
     */
    public static function add($feed_id, $trackid = '', $user_id = null, $sockpuppet = 0){
        $user_id = UserService::makeUserID($user_id);
        
        if(!FeedService::isFeedIdExist($feed_id)){
            throw new Exception('指定的动态ID不存在', 'the-given-feed-id-is-not-exist');
        }
        
        if(self::isLiked($feed_id, $user_id)){
            throw new Exception('已赞过，不能重复点赞', 'already-liked');
        }
        
        FeedLikesTable::model()->insert(array(
            'feed_id'=>$feed_id,
            'user_id'=>$user_id,
            'create_time'=>\F::app()->current_time,
            'trackid'=>$trackid,
            'sockpuppet'=>$sockpuppet,
        ));
        
        //动态点赞数+1
        if($sockpuppet){
            //非真实用户行为
            FeedMetaTable::model()->incr($feed_id, array('likes'), 1);
        }else{
            //真实用户行为
            FeedMetaTable::model()->incr($feed_id, array('likes', 'real_likes'), 1);
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_LIKE, $feed_id);
    }
    
    /**
     * 取消点赞
     * @param int $feed_id 动态ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function remove($feed_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        $like = FeedLikesTable::model()->find(array($feed_id, $user_id), 'sockpuppet');
        if($like){
            //删除点赞关系
            FeedLikesTable::model()->delete(array(
                'user_id = ?'=>$user_id,
                'feed_id = ?'=>$feed_id,
            ));
            
            if($like['sockpuppet']){
                //非真实用户行为
                FeedMetaTable::model()->incr($feed_id, array('likes'), -1);
            }else{
                //真实用户行为
                FeedMetaTable::model()->incr($feed_id, array('likes', 'real_likes'), -1);
            }
            
            //触发事件
            \F::event()->trigger(self::EVENT_UNLIKE, $feed_id);
            
            return true;
        }else{
            //未点赞
            return false;
        }
    }
    
    /**
     * 判断是否赞过
     * @param int $feed_id 动态ID
     * @param int|null $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function isLiked($feed_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        if(FeedLikesTable::model()->find(array($feed_id, $user_id))){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 批量判断是否赞过
     * @param array $feed_ids 由动态ID组成的一维数组
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return array
     * @throws Exception
     */
    public static function mIsLiked($feed_ids, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        if(!is_array($feed_ids)){
            $feed_ids = explode(',', str_replace(' ', '', $feed_ids));
        }
        
        $likes = FeedLikesTable::model()->fetchAll(array(
            'user_id = ?'=>$user_id,
            'feed_id IN (?)'=>$feed_ids,
        ), 'feed_id');
        
        $like_map = ArrayHelper::column($likes, 'feed_id');
        
        $return = array();
        foreach($feed_ids as $p){
            $return[$p] = in_array($p, $like_map);
        }
        
        return $return;
    }
}