<?php
namespace cms\services\post;

use fay\common\ListView;
use fay\core\Exception;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use cms\models\tables\PostsTable;
use cms\services\user\UserService;
use cms\models\tables\PostFavoritesTable;
use fay\helpers\RequestHelper;
use cms\models\tables\PostMetaTable;

class PostFavoriteService extends Service{
    /**
     * 文章被收藏后事件
     */
    const EVENT_FAVORITED = 'after_post_favorite';
    
    /**
     * 文章被取消收藏后事件
     */
    const EVENT_CANCEL_FAVORITED = 'after_post_cancel_favorite';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 收藏文章
     * @param int $post_id 文章ID
     * @param string $trackid
     * @param int $user_id 用户ID，默认为当前登录用户
     * @param int $sockpuppet
     * @throws Exception
     */
    public static function add($post_id, $trackid = '', $user_id = null, $sockpuppet = 0){
        $user_id = UserService::getUserId($user_id);
        
        if(!PostService::isPostIdExist($post_id)){
            throw new Exception('文章ID不存在', 'invalid-parameter:post_id-not-exist');
        }
        
        if(self::isFavorited($post_id, $user_id)){
            throw new Exception('已收藏，不能重复收藏', 'already-favorited');
        }
        
        PostFavoritesTable::model()->insert(array(
            'user_id'=>$user_id,
            'post_id'=>$post_id,
            'trackid'=>$trackid,
            'sockpuppet'=>$sockpuppet,
            'create_time'=>\F::app()->current_time,
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
        ));
        
        //文章收藏数+1
        if($sockpuppet){
            //非真实用户行为
            PostMetaTable::model()->incr($post_id, array('favorites'), 1);
        }else{
            //真实用户行为
            PostMetaTable::model()->incr($post_id, array('favorites', 'real_favorites'), 1);
        }
        
        \F::event()->trigger(self::EVENT_FAVORITED, $post_id);
    }
    
    /**
     * 取消收藏
     * @param int $post_id 文章ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function remove($post_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        $favorite = PostFavoritesTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
            'post_id = ?'=>$post_id,
        ), 'sockpuppet');
        if($favorite){
            //删除收藏关系
            PostFavoritesTable::model()->delete(array(
                'user_id = ?'=>$user_id,
                'post_id = ?'=>$post_id,
            ));
            
            //文章收藏数-1
            if($favorite['sockpuppet']){
                //非真实用户行为
                PostMetaTable::model()->incr($post_id, array('favorites'), -1);
            }else{
                //真实用户行为
                PostMetaTable::model()->incr($post_id, array('favorites', 'real_favorites'), -1);
            }
                
            //触发事件
            \F::event()->trigger(self::EVENT_CANCEL_FAVORITED, $post_id);
                
            return true;
        }else{
            //未点赞
            return false;
        }
    }
    
    /**
     * 判断是否收藏过
     * @param int $post_id 文章ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function isFavorited($post_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        return !!PostFavoritesTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
            'post_id = ?'=>$post_id,
        ), 'id');
    }
    
    /**
     * 批量判断是否收藏过
     * @param array $post_ids 由文章ID组成的一维数组
     * @param int|null $user_id 用户ID，默认为当前登录用户
     * @return array
     * @throws Exception
     */
    public static function mIsFavorited($post_ids, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        if(!is_array($post_ids)){
            $post_ids = explode(',', str_replace(' ', '', $post_ids));
        }
        
        $favorites = PostFavoritesTable::model()->fetchAll(array(
            'user_id = ?'=>$user_id,
            'post_id IN (?)'=>$post_ids,
        ), 'post_id');
        
        $favorite_map = ArrayHelper::column($favorites, 'post_id');
        
        $return = array();
        foreach($post_ids as $p){
            $return[$p] = in_array($p, $favorite_map);
        }
        return $return;
    }
    
    /**
     * 获取收藏列表
     * @param string $fields 文章字段
     * @param int $page
     * @param int $page_size
     * @param int|null $user_id 用户ID，默认为当前登录用户
     * @return array
     * @throws Exception
     */
    public function getList($fields, $page = 1, $page_size = 20, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        $sql = new Sql();
        $sql->from(array('pf'=>'post_favorites'), 'post_id')
            ->joinLeft(array('p'=>'posts'), 'pf.post_id = p.id')
            ->where('pf.user_id = ?', $user_id)
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('pf.id DESC')
        ;
        
        $listview = new ListView($sql, array(
            'page_size'=>$page_size,
            'current_page'=>$page,
        ));
        
        $favorites = $listview->getData();
        
        if(!$favorites){
            return array();
        }
        
        return array(
            'favorites'=>PostService::service()->mget(
                ArrayHelper::column($favorites, 'post_id'),
                $fields
            ),
            'pager'=>$listview->getPager(),
        );
    }
}