<?php
namespace cms\services\post;

use cms\models\tables\PostExtraTable;
use cms\models\tables\PostHistoriesTable;
use cms\models\tables\PostsTable;
use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fay\helpers\RequestHelper;

/**
 * 文章历史
 */
class PostHistoryService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 创建历史
     * @param int $post_id 文章ID
     * @param null|int $user_id
     * @return int
     * @throws PostErrorException
     */
    public function create($post_id, $user_id = null){
        $user_id = UserService::getUserId($user_id);

        $post = PostsTable::model()->find($post_id, 'id,title,content,content_type,cat_id,thumbnail,abstract');
        if(!$post){
            throw new PostErrorException("指定文章ID[{$post_id}]不存在", 'the-given-post-id-is-not-exist');
        }

        $data = array(
            'post_id'=>$post['id'],
            'title'=>$post['title'],
            'content'=>$post['content'],
            'content_type'=>$post['content_type'],
            'cat_id'=>$post['cat_id'],
            'thumbnail'=>$post['thumbnail'],
            'abstract'=>$post['abstract'],
        );

        if($data['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
            $extra = PostExtraTable::model()->find($data['post_id']);
            //若是markdown语法保存的文章，content字段存储markdown原文
            $data['content'] = $extra['markdown'];
        }

        if($this->equalLastRecord($data)){
            //若给定文章内容与之前最后一条历史记录完全相同，则不记录
            return '0';
        }

        $data['user_id'] = $user_id;
        $data['create_time'] = \F::app()->current_time;
        $data['ip_int'] = RequestHelper::ip2int(\F::app()->ip);

        return PostHistoriesTable::model()->insert($data, true);
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
    public function getPostHistories($post_id, $fields = '*', $limit = 10, $last_id = 0){
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
        
        return ArrayHelper::equal($last_history, $post);
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

    /**
     * 根据文章ID，获取对应历史存档数量
     * @param int $post_id
     * @return int
     */
    public function getCount($post_id){
        $count = PostHistoriesTable::model()->fetchRow(array(
            'post_id = ?'=>$post_id,
        ), 'COUNT(*)');
        return $count['COUNT(*)'];
    }

    /**
     * 根据历史版本id永久删除一个版本
     * 本来就是历史，没什么必要再做假删了
     * @param int $id
     * @return int
     */
    public function remove($id){
        return PostHistoriesTable::model()->delete($id);
    }

    /**
     * 根据文章ID，删除文章对应的所有历史版本记录
     * @param int $post_id
     * @return int
     */
    public function removeAll($post_id){
        return PostHistoriesTable::model()->delete(array(
            'post_id = ?'=>$post_id
        ));
    }

    /**
     * 将文章恢复至历史版本
     * @param int $history_id 历史版本ID
     * @return bool
     * @throws PostErrorException
     */
    public function revert($history_id){
        $history = PostHistoriesTable::model()->find($history_id);
        if(!$history){
            throw new PostErrorException("指定文章历史ID[{$history_id}]不存在", 'the-given-history-id-is-not-exist');
        }

        $extra = array();
        $content = $history['content'];
        if($history['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
            //如果是markdown语法的历史，content转为html
            $extra['extra'] = array(
                'markdown'=>$history['content']
            );
            $content = \Michelf\Markdown::defaultTransform($history['content']);
        }

        return PostService::service()->update($history['post_id'], array(
            'title'=>$history['title'],
            'content'=>$content,
            'content_type'=>$history['content_type'],
            'cat_id'=>$history['cat_id'],
            'thumbnail'=>$history['thumbnail'],
            'abstract'=>$history['abstract'],
        ), $extra);
    }

    /**
     * 获取指定历史上一篇历史记录，若无上一篇记录，返回false
     * @param $history_id
     * @param string $fields
     * @return array|false
     * @throws PostErrorException
     */
    public function getPreviewHistory($history_id, $fields = '*'){
        $history = PostHistoriesTable::model()->find($history_id, 'id,post_id');
        if(!$history){
            throw new PostErrorException("指定文章历史ID[{$history_id}]不存在", 'the-given-history-id-is-not-exist');
        }

        return PostHistoriesTable::model()->fetchRow(array(
            'post_id = ' . $history['post_id'],
            'id < ' . $history['id'],
        ), $fields, 'id DESC');
    }
}