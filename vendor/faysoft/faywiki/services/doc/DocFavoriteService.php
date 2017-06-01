<?php
namespace faywiki\services\doc;

use fay\common\ListView;
use fay\core\Exception;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use faywiki\models\tables\WikiDocsTable;
use cms\services\user\UserService;
use faywiki\models\tables\WikiDocFavoritesTable;
use fay\helpers\RequestHelper;
use faywiki\models\tables\WikiDocMetaTable;

class DocFavoriteService extends Service{
    /**
     * 文档被收藏后事件
     */
    const EVENT_FAVORITED = 'after_doc_favorite';
    
    /**
     * 文档被取消收藏后事件
     */
    const EVENT_CANCEL_FAVORITED = 'after_doc_cancel_favorite';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 收藏文档
     * @param int $doc_id 文档ID
     * @param string $trackid
     * @param int $user_id 用户ID，默认为当前登录用户
     * @param int $sockpuppet
     * @throws Exception
     */
    public static function add($doc_id, $trackid = '', $user_id = null, $sockpuppet = 0){
        $user_id = UserService::getUserId($user_id);
        
        if(!DocService::isDocIdExist($doc_id)){
            throw new Exception("指定文档ID[{$doc_id}]不存在", 'the-given-doc-id-is-not-exist');
        }
        
        if(self::isFavorited($doc_id, $user_id)){
            throw new Exception('已收藏，不能重复收藏', 'already-favorited');
        }
        
        WikiDocFavoritesTable::model()->insert(array(
            'user_id'=>$user_id,
            'doc_id'=>$doc_id,
            'trackid'=>$trackid,
            'sockpuppet'=>$sockpuppet,
            'create_time'=>\F::app()->current_time,
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
        ));
        
        //文档收藏数+1
        if($sockpuppet){
            //非真实用户行为
            WikiDocMetaTable::model()->incr($doc_id, array('favorites'), 1);
        }else{
            //真实用户行为
            WikiDocMetaTable::model()->incr($doc_id, array('favorites', 'real_favorites'), 1);
        }
        
        \F::event()->trigger(self::EVENT_FAVORITED, $doc_id);
    }
    
    /**
     * 取消收藏
     * @param int $doc_id 文档ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function remove($doc_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        $favorite = WikiDocFavoritesTable::model()->find(array(
            'user_id = ?'=>$user_id,
            'doc_id = ?'=>$doc_id,
        ), 'sockpuppet');
        if($favorite){
            //删除收藏关系
            WikiDocFavoritesTable::model()->delete(array(
                'user_id = ?'=>$user_id,
                'doc_id = ?'=>$doc_id,
            ));
            
            //文档收藏数-1
            if($favorite['sockpuppet']){
                //非真实用户行为
                WikiDocMetaTable::model()->incr($doc_id, array('favorites'), -1);
            }else{
                //真实用户行为
                WikiDocMetaTable::model()->incr($doc_id, array('favorites', 'favorites'), -1);
            }
                
            //触发事件
            \F::event()->trigger(self::EVENT_CANCEL_FAVORITED, $doc_id);
                
            return true;
        }else{
            //未点赞
            return false;
        }
    }
    
    /**
     * 判断是否收藏过
     * @param int $doc_id 文档ID
     * @param int $user_id 用户ID，默认为当前登录用户
     * @return bool
     * @throws Exception
     */
    public static function isFavorited($doc_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        return !!WikiDocFavoritesTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
            'doc_id = ?'=>$doc_id,
        ), 'id');
    }
    
    /**
     * 批量判断是否收藏过
     * @param array $doc_ids 由文档ID组成的一维数组
     * @param int|null $user_id 用户ID，默认为当前登录用户
     * @return array
     * @throws Exception
     */
    public static function mIsFavorited($doc_ids, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
        }
        
        if(!is_array($doc_ids)){
            $doc_ids = explode(',', str_replace(' ', '', $doc_ids));
        }
        
        $favorites = WikiDocFavoritesTable::model()->fetchAll(array(
            'user_id = ?'=>$user_id,
            'doc_id IN (?)'=>$doc_ids,
        ), 'doc_id');
        
        $favorite_map = ArrayHelper::column($favorites, 'doc_id');
        
        $return = array();
        foreach($doc_ids as $p){
            $return[$p] = in_array($p, $favorite_map);
        }
        return $return;
    }
    
    /**
     * 获取收藏列表
     * @param string $fields 文档字段
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
        $sql->from(array('df'=>'wiki_doc_favorites'), 'doc_id')
            ->joinLeft(array('d'=>'wiki_docs'), 'df.doc_id = d.id')
            ->where('df.user_id = ?', $user_id)
            ->where(WikiDocsTable::getPublishedConditions('p'))
            ->order('df.id DESC')
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
            'favorites'=>DocService::service()->mget(
                ArrayHelper::column($favorites, 'doc_id'),
                $fields
            ),
            'pager'=>$listview->getPager(),
        );
    }
}