<?php
namespace cms\services;

use cms\models\tables\FollowsTable;
use cms\models\tables\UserCounterTable;
use cms\services\user\UserService;
use fay\common\ListView;
use fay\core\Exception;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldsHelper;

/**
 * 关注服务
 */
class FollowService extends Service{
    /**
     * 添加关注后事件
     */
    const EVENT_FOLLOW = 'after_follow';
    
    /**
     * 取消关注后事件
     */
    const EVENT_UNFOLLOW = 'after_unfollow';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 关注一个用户
     * @param int $user_id 要关注的人
     * @param string $trackid
     * @param null|int $fan_id 粉丝，默认为当前登陆用户
     * @param int $sockpuppet 马甲信息
     * @return int
     * @throws Exception
     */
    public static function add($user_id, $trackid = '', $fan_id = null, $sockpuppet = 0){
        if($fan_id === null){
            $fan_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($fan_id)){
            throw new Exception('指定粉丝ID不存在', 'the-given-fans-id-is-not-exist');
        }
        
        if($user_id == $fan_id){
            throw new Exception('粉丝和用户ID不能相同', 'can-not-follow-yourself');
        }
        
        if(!UserService::isUserIdExist($user_id)){
            throw new Exception("关注的用户ID[{$user_id}]不存在", 'the-given-fans-id-is-not-exist');
        }
        
        if(self::isFollow($user_id, $fan_id)){
            throw new Exception('已关注，不能重复关注', 'already-followed');
        }
        
        $isFollow = self::isFollow($fan_id, $user_id);
        FollowsTable::model()->insert(array(
            'fans_id'=>$fan_id,
            'user_id'=>$user_id,
            'create_time'=>\F::app()->current_time,
            'ip_int'=>\F::app()->ip_int,
            'relation'=>$isFollow ? FollowsTable::RELATION_BOTH : FollowsTable::RELATION_SINGLE,
            'sockpuppet'=>$sockpuppet,
            'trackid'=>$trackid,
        ));
        
        if($isFollow){
            //若存在反向关注，则将反向关注记录的relation也置为双向关注
            FollowsTable::model()->update(array(
                'relation'=>FollowsTable::RELATION_BOTH,
            ), array(
                'user_id = ?'=>$fan_id,
                'fans_id = ?'=>$user_id,
            ));
        }
        
        //关注用户关注数+1
        UserCounterTable::model()->incr($fan_id, 'follows', 1);
        
        //被关注用户粉丝数+1
        UserCounterTable::model()->incr($user_id, 'fans', 1);
        
        //触发事件
        \F::event()->trigger(self::EVENT_FOLLOW, array(
            'user_id'=>$user_id,
            'fan_id'=>$fan_id,
        ));
        
        return $isFollow ? FollowsTable::RELATION_BOTH : FollowsTable::RELATION_SINGLE;
    }
    
    /**
     * 取消关注（若实际在未关注状态，并不抛异常，返回false）
     * @param int $user_id 取消关注的人
     * @param null|int $fan_id 粉丝，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function remove($user_id, $fan_id = null){
        $fan_id || $fan_id = \F::app()->current_user;
        if(!$fan_id){
            throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
        }
        
        if(self::isFollow($user_id, $fan_id)){//是关注状态，执行取消关注
            //删除关注关系
            FollowsTable::model()->delete(array(
                'fans_id = ?'=>$fan_id,
                'user_id = ?'=>$user_id,
            ));
            
            //若互相关注，则更新反向关注的关注关系
            if(self::isFollow($fan_id, $user_id)){
                FollowsTable::model()->update(array(
                    'relation'=>FollowsTable::RELATION_SINGLE,
                ), array(
                    'fans_id = ?'=>$user_id,
                    'user_id = ?'=>$fan_id,
                ));
            }
            
            //关注用户关注数-1
            UserCounterTable::model()->incr($fan_id, 'follows', -1);
            
            //被关注用户粉丝数-1
            UserCounterTable::model()->incr($user_id, 'fans', -1);
            
            //触发事件
            \F::event()->trigger(self::EVENT_UNFOLLOW, array(
                'user_id'=>$user_id,
                'fan_id'=>$fan_id,
            ));
            
            return true;
        }else{//不是关注状态，也不抛异常
            return false;
        }
    }
    
    /**
     * 判断第二个用户ID是不是关注了第一个用户ID
     * @param int $user_id 被关注的人ID
     * @param string $fan_id 粉丝ID，默认为当前登录用户
     * @return int 0-未关注;1-单向关注;2-双向关注
     * @throws Exception
     */
    public static function isFollow($user_id, $fan_id = null){
        $fan_id || $fan_id = \F::app()->current_user;
        if(!$fan_id){
            throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
        }
        
        $follow = FollowsTable::model()->find(array($fan_id, $user_id), 'relation');
        if($follow){
            return intval($follow['relation']);//为了保证返回字段类型一致，这里intval一下
        }else{
            return 0;
        }
    }
    
    /**
     * 批量判断是否关注
     * @param array $user_ids 由用户ID组成的一维数组
     * @param string $fan_id 粉丝ID，默认为当前登录用户
     * @return array 根据传入的$user_ids为数组的键，对应值为:0-未关注;1-单向关注;2-双向关注
     * @throws Exception
     */
    public static function mIsFollow($user_ids, $fan_id = null){
        $fan_id || $fan_id = \F::app()->current_user;
        if(!$fan_id){
            throw new Exception('未能获取到粉丝ID', 'can-not-find-a-effective-fans-id');
        }
        
        $follows = FollowsTable::model()->fetchAll(array(
            'fans_id = ?'=>$fan_id,
            'user_id IN (?)'=>$user_ids,
        ), 'user_id,relation');
        
        $follow_map = ArrayHelper::column($follows, 'relation', 'user_id');
        
        $return = array();
        foreach($user_ids as $u){
            $return[$u] = isset($follow_map[$u]) ? intval($follow_map[$u]) : 0;
        }
        return $return;
    }
    
    /**
     * 关注列表
     * @param int $user_id 用户ID
     * @param string $fields 字段
     * @param int $page 页码
     * @param int $page_size 分页大小
     * @return array
     */
    public static function follows($user_id = null, $fields = 'relation,user.id,user.nickname,user.avatar', $page = 1, $page_size = 20){
        $user_id || $user_id = \F::app()->current_user;
        $fields = new FieldsHelper($fields, 'follows');
        
        $follows_fields = $fields->getFields();
        if($fields->user && !in_array('user_id', $follows_fields)){
            $follows_fields[] = 'user_id';
        }
        
        $sql = new Sql();
        $sql->from(array('f'=>'follows'), $follows_fields)
            ->where('fans_id = ?', $user_id)
            ->order('create_time DESC')
        ;
        $listview = new ListView($sql, array(
            'page_size'=>$page_size,
            'current_page'=>$page,
        ));
        
        $follows = $listview->getData();
        
        $return = array(
            'follows'=>array(),
            'pager'=>$listview->getPager(),
        );
        
        if($follows && $fields->user){
            $users = UserService::service()->mget(ArrayHelper::column($follows, 'user_id'), $fields->user);
        }
        
        foreach($follows as $f){
            $follow = array();
            foreach($fields->getFields() as $field){
                $follow['follow'][$field] = $f[$field];
            }
            
            if(isset($users)){
                $follow['user'] = $users[$f['user_id']];
            }
            
            $return['follows'][] = $follow;
        }
        
        return $return;
    }
    
    /**
     * 粉丝列表
     * @param int $user_id 用户ID
     * @param string $fields 字段
     * @param int $page 页码
     * @param int $page_size 分页大小
     * @return array
     */
    public static function fans($user_id = null, $fields = 'follows.relation,user.id,user.nickname,user.avatar', $page = 1, $page_size = 20){
        $user_id || $user_id = \F::app()->current_user;
        $fields = new FieldsHelper($fields, 'follows');
        
        $follows_fields = $fields->getFields();
        if($fields->user && !in_array('fans_id', $follows_fields)){
            $follows_fields[] = 'fans_id';
        }
        
        $sql = new Sql();
        $sql->from(array('f'=>'follows'), $follows_fields)
            ->where('user_id = ?', $user_id)
            ->order('create_time DESC')
        ;
        $listview = new ListView($sql, array(
            'page_size'=>$page_size,
            'current_page'=>$page,
        ));
        
        $fans = $listview->getData();
        
        $return = array(
            'fans'=>array(),
            'pager'=>$listview->getPager(),
        );
        
        if($fans && !empty($fields->user)){
            $users = UserService::service()->mget(ArrayHelper::column($fans, 'fans_id'), $fields->user);
        }
        
        foreach($fans as $f){
            $follow = array();
            foreach($fields->getFields() as $field){
                $follow['follow'][$field] = $f[$field];
            }
            
            if(isset($users)){
                $follow['user'] = $users[$f['fans_id']];
            }
            
            $return['fans'][] = $follow;
        }
        
        return $return;
    }
}