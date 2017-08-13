<?php
namespace fayfeed\services;

use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fayfeed\models\tables\FeedFavoritesTable;
use fayfeed\models\tables\FeedMetaTable;

class FeedFavoriteService extends Service{
    /**
     * 动态被收藏后事件
     */
    const EVENT_FAVORITE = 'after_feed_favorite';
    
    /**
     * 动态被取消收藏后事件
     */
    const EVENT_CANCEL_FAVORITE = 'after_feed_cancel_favorite';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 收藏动态
     * @param int $feed_id 动态ID
     * @param string $trackid
     * @param int $user_id 用户ID，默认为当前登录用户
     * @param int $sockpuppet
     */
    public static function add($feed_id, $trackid = '', $user_id = null, $sockpuppet = 0){
        $user_id = UserService::makeUserID($user_id);
        
        if(!FeedService::isFeedIdExist($feed_id)){
            throw new FeedNotExistException($feed_id);
        }
        
        if(self::isFavorited($feed_id, $user_id)){
            throw new \RuntimeException('已收藏，不能重复收藏');
        }
        
        FeedFavoritesTable::model()->insert(array(
            'user_id'=>$user_id,
            'feed_id'=>$feed_id,
            'trackid'=>$trackid,
            'sockpuppet'=>$sockpuppet,
            'create_time'=>\F::app()->current_time,
            'ip_int'=>\F::app()->ip_int,
        ));
        
        //动态收藏数+1
        if($sockpuppet){
            //非真实用户行为
            FeedMetaTable::model()->incr($feed_id, array('favorites'), 1);
        }else{
            //真实用户行为
            FeedMetaTable::model()->incr($feed_id, array('favorites', 'real_favorites'), 1);
        }
        
        \F::event()->trigger(self::EVENT_FAVORITE, $feed_id);
    }
    
    /**
     * 取消收藏
     * @param int $feed_id 动态ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     */
    public static function remove($feed_id, $user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        $favorite = FeedFavoritesTable::model()->find(array($user_id, $feed_id), 'sockpuppet');
        if($favorite){
            //删除收藏关系
            FeedFavoritesTable::model()->delete(array(
                'user_id = ?'=>$user_id,
                'feed_id = ?'=>$feed_id,
            ));
            
            //动态收藏数-1
            if($favorite['sockpuppet']){
                //非真实用户行为
                FeedMetaTable::model()->incr($feed_id, array('favorites'), -1);
            }else{
                //真实用户行为
                FeedMetaTable::model()->incr($feed_id, array('favorites', 'real_favorites'), -1);
            }
                
            //触发事件
            \F::event()->trigger(self::EVENT_CANCEL_FAVORITE, $feed_id);
                
            return true;
        }else{
            //未点赞
            return false;
        }
    }
    
    /**
     * 判断是否收藏过
     * @param int $feed_id 动态ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     */
    public static function isFavorited($feed_id, $user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        if(FeedFavoritesTable::model()->find(array($user_id, $feed_id), 'create_time')){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 批量判断是否收藏过
     * @param array $feed_ids 由动态ID组成的一维数组
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return array
     */
    public static function mIsFavorited($feed_ids, $user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        if(!is_array($feed_ids)){
            $feed_ids = explode(',', str_replace(' ', '', $feed_ids));
        }
        
        $favorites = FeedFavoritesTable::model()->fetchAll(array(
            'user_id = ?'=>$user_id,
            'feed_id IN (?)'=>$feed_ids,
        ), 'feed_id');
        
        $favorite_map = ArrayHelper::column($favorites, 'feed_id');
        
        $return = array();
        foreach($feed_ids as $p){
            $return[$p] = in_array($p, $favorite_map);
        }
        return $return;
    }
}