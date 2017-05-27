<?php
namespace guangong\services;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\core\Service;
use cms\models\tables\PostsTable;
use cms\services\CategoryService;
use cms\services\user\UserService;
use guangong\models\tables\GuangongReadLogsTable;

class PostService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取用户下一篇可读文献
     * @param null|int $user_id
     * @return int
     * @throws ErrorException
     */
    public function getNextPost($user_id = null){
        if($user_id === null){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new ErrorException('指定用户ID不存在', 'user-id-is-not-exist');
        }
        
        //尝试获取今天阅读的文献
        $today_read_post = GuangongReadLogsTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
            'create_date = ?'=>date('Y-m-d'),
        ), 'post_id');
        if($today_read_post){
            return $today_read_post['post_id'];
        }
            
        //获取所有已读文献
        $read_post_ids = GuangongReadLogsTable::model()->fetchCol('post_id', array(
            'user_id = ?'=>$user_id,
        ));
        
        //获取文献分类及其子分类所有ID
        $root_cat = CategoryService::service()->get('arm-info');
        $cat_ids = CategoryService::service()->getChildIds('arm-info');
        $cat_ids[] = $root_cat['id'];
        
        $post = PostsTable::model()->fetchRow(array(
            'id NOT IN (?)'=>$read_post_ids ? $read_post_ids : false,
            'cat_id IN (?)'=>$cat_ids,
        ), 'id', 'sort, id');
        
        return $post ? $post['id'] : '0';
    }
}