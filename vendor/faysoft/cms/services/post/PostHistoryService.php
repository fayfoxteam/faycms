<?php
namespace cms\services\post;

use cms\models\tables\PostHistoriesTable;
use cms\models\tables\PostsTable;
use cms\services\user\UserService;
use fay\core\Service;
use fay\helpers\RequestHelper;

/**
 * 文章历史
 */
class PostHistoryService extends Service{
    /**
     * @param string $class_name
     * @return PostHistoryService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 创建历史
     * @param array $post
     * @param null|int $user_id
     * @return int
     * @throws PostErrorException
     */
    public function create($post, $user_id = null){
        if($user_id === null){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new PostErrorException("指定用户ID[{$user_id}]不存在", 'the-given-user-id-is-not-exist');
        }
        
        if($checkResult = $this->checkFields($post)){
            throw new PostErrorException("历史记录缺少字段[{$checkResult}]");
        }
        
        if($post['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
            //若是markdown语法保存的文章，content字段存储markdown原文
            if(!isset($post['markdown'])){
                throw new PostErrorException('MarkDown语法编辑的文章，生成历史记录时必须包含MarkDown字段');
            }
            $post['content'] = $post['markdown'];
        }
        
        if($this->equalLastRecord($post)){
            //若给定文章内容与之前最后一条历史记录完全相同，则不记录
            return '0';
        }
        
        $post['user_id'] = $user_id;
        $post['create_time'] = \F::app()->current_time;
        $post['ip_int'] = RequestHelper::ip2int(\F::app()->ip);
        
        return PostHistoriesTable::model()->insert($post, true);
    }
    
    /**
     * 检查给定的记录信息是否包含所有字段（并不限制多余字段）
     * @param array $post
     * @return string
     */
    protected function checkFields($post){
        $fields = PostHistoriesTable::model()->getFields(array(
            'id', 'user_id', 'create_time', 'ip_int'
        ));
        foreach($fields as $field){
            if(!isset($post[$field])){
                return $field;
            }
        }
        
        return '';
    }
    
    /**
     * 根据文章ID，获取文章历史
     * @param int $post_id 文章ID
     * @param string $fields 指定字段
     * @param int $limit 获取历史数量
     * @param int $last_id 用于分页
     * @return array
     */
    public function getPostHistory($post_id, $fields = '*', $limit = 10, $last_id = 0){
        return PostHistoriesTable::model()->fetchAll(array(
            'post_id = ?'=>$post_id,
            'id < ?'=>$last_id ? $last_id : false,
        ), $fields, 'id DESC', $limit);
    }
    
    /**
     * 比较给出的文章内容与上一次历史记录是否完全一致
     * @param array $post 可能会包含多余字段，多余字段不参与比较
     * @return bool
     * @throws PostErrorException
     */
    public function equalLastRecord($post){
        $last_history = $this->getLastHistory($post['post_id'], '!id,user_id,create_time,ip_int');
        if(!$last_history){
            //若没有老的历史记录，直接返回false
            return false;
        }
        foreach($last_history as $key => $val){
            if(!isset($post[$key]) || $post[$key] != $val){
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 根据文章ID，获取其最后一篇历史
     * @param $post_id
     * @param string $fields
     * @return array|bool
     */
    public function getLastHistory($post_id, $fields = '*'){
        return PostHistoriesTable::model()->fetchRow(array(
            'post_id = ?'=>$post_id,
        ), $fields, 'id DESC');
    }
    
    /**
     * 根据历史记录ID获取一条历史记录
     * @param int $id 历史记录ID
     * @param string $fields 字段
     * @return array|bool
     */
    public function get($id, $fields = '*'){
        return PostHistoriesTable::model()->find($id, $fields);
    }
}