<?php
namespace faywiki\services\doc;

use cms\services\CategoryService;
use cms\services\doc\DocExtraService;
use cms\services\file\FileService;
use cms\services\prop\PropService;
use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\FieldHelper;
use fay\helpers\RequestHelper;
use faywiki\models\tables\PropsTable;
use faywiki\models\tables\WikiDocExtraTable;
use faywiki\models\tables\WikiDocFavoritesTable;
use faywiki\models\tables\WikiDocHistoriesTable;
use faywiki\models\tables\WikiDocLikesTable;
use faywiki\models\tables\WikiDocMetaTable;
use faywiki\models\tables\WikiDocPropHistoriesTable;
use faywiki\models\tables\WikiDocPropIntTable;
use faywiki\models\tables\WikiDocPropTextTable;
use faywiki\models\tables\WikiDocPropVarcharTable;
use faywiki\models\tables\WikiDocsTable;

class DocService extends Service{
    /**
     * 文档创建后事件
     */
    const EVENT_CREATED = 'after_doc_created';

    /**
     * 文档更新后事件
     */
    const EVENT_UPDATED = 'after_doc_updated';

    /**
     * 文档被删除后事件
     */
    const EVENT_DELETED = 'after_doc_deleted';

    /**
     * 文档被还原后事件
     */
    const EVENT_UNDELETE = 'after_doc_undelete';

    /**
     * 文章被物理删除事件
     */
    const EVENT_REMOVING = 'before_doc_removed';
    
    /**
     * 允许在接口调用时返回的字段
     */
    public static $public_fields = array(
        'doc'=>array(
            'id', 'user_id', 'cat_id', 'title', 'abstract', 'thumbnail', 'create_time', 'write_lock',
        ),
        'category'=>array(
            'id', 'title', 'alias',
        ),
        'user'=>array(
            'id', 'nickname', 'avatar',
        ),
        'props'=>array(
            '*',//这里指定的是属性别名，取值视后台设定而定
        ),
        'meta'=>array(
            'views', 'likes', 'favorites', 'shares',
        ),
        'extra'=>array(
            'seo_title', 'seo_keywords', 'seo_description',
        ),
    );

    /**
     * 默认接口返回字段
     */
    public static $default_fields = array(
        'doc'=>array(
            'fields'=>array(
                'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
            )
        ),
        'category'=>array(
            'fields'=>array(
                'id', 'title', 'alias',
            )
        ),
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
     * 创建一篇文档
     * @param array $doc docs表相关字段
     * @param array $extra 其它字段
     *  - meta 计数信息，对应wiki_doc_meta表字段。
     *  - extra 扩展信息，对应wiki_doc_extra表字段。
     *  - props 以属性ID为键，属性值为值构成的关联数组
     * @param int $user_id 作者ID
     * @return int 文档ID
     * @throws DocErrorException
     */
    public function create($doc, $extra, $user_id = null){
        //确定作者
        $user_id = UserService::getUserId($user_id);

        //验证分类
        if(!empty($doc['cat_id']) && !CategoryService::service()->isIdExist($doc['cat_id'], '_system_wiki_doc')){
            throw new DocErrorException("指定分类ID[{$doc['cat_id']}]不存在");
        }

        $doc['create_time'] = \F::app()->current_time;
        $doc['update_time'] = \F::app()->current_time;
        $doc['user_id'] = $user_id;

        //过滤掉多余的数据，并插入文档表
        $doc_id = WikiDocsTable::model()->insert($doc, true);

        //状态将用于后面统计逻辑
        if(isset($doc['status'])){
            $doc_status = $doc['status'];
        }else{
            $db_doc = WikiDocsTable::model()->find($doc, 'status');
            $doc_status = $db_doc['status'];
        }

        //扩展信息
        $doc_extra = array(
            'doc_id'=>$doc_id,
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
        );
        if(isset($extra['extra'])){
            $doc_extra = $doc_extra + $extra['extra'];
        }
        WikiDocExtraTable::model()->insert($doc_extra);

        //更新文档分类文档数
        if(!empty($doc['cat_id'])){
            DocCategoryService::service()->updateCatCount(null, $doc['cat_id'], null, $doc_status);
        }

        //Meta
        $doc_meta = array(
            'doc_id'=>$doc_id,
        );
        if(isset($extra['meta'])){
            $doc_meta = $doc_meta + $extra['meta'];
        }
        WikiDocMetaTable::model()->insert($doc_meta);
        
        //自定义属性
        if(isset($extra['props'])){
            DocPropService::service()->createPropSet(
                $doc_id,
                $extra['props']['data'],
                isset($extra['props']['labels']) ? $extra['props']['labels'] : array()
            );
        }

        if($doc_status == WikiDocsTable::STATUS_PUBLISHED){
            //用户文档数加一
            DocUserCounterService::service()->incr($user_id);
        }

        //触发事件
        \F::event()->trigger(self::EVENT_CREATED, $doc_id);

        return $doc_id;
    }

    /**
     * 更新一篇文档
     * @param int $doc_id 文档ID
     * @param array $data docs表相关字段
     * @param array $extra 其它字段
     *  - meta 计数信息，对应wiki_doc_meta表字段。若不传，则不会更新
     *  - extra 扩展信息，对应wiki_doc_extra表字段。若不传，则不会更新
     *  - props 以属性ID为键，属性值为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空属性。
     * @param bool $update_update_time 是否更新“最后更新时间”。默认为true
     * @return bool
     * @throws DocErrorException
     */
    public function update($doc_id, $data, $extra, $update_update_time = true){
        //获取原文档
        $old_doc = WikiDocsTable::model()->find($doc_id, 'cat_id,user_id,delete_time,status');
        if(!$old_doc){
            throw new DocErrorException('指定文档不存在');
        }
        if($old_doc['delete_time']){
            throw new DocErrorException('已删除文档不允许编辑');
        }

        if($update_update_time){
            $data['update_time'] = \F::app()->current_time;
        }else if(isset($data['update_time'])){
            unset($data['update_time']);
        }

        //过滤掉多余的字段后更新
        WikiDocsTable::model()->update($data, $doc_id, true);

        //更新主分类文档数
        DocCategoryService::service()->updateCatCount(
            $old_doc['cat_id'],
            isset($data['cat_id']) ? $data['cat_id'] : null,
            $old_doc['status'],
            isset($data['status']) ? $data['status'] : null
        );

        //计数表
        if(!empty($extra['meta'])){
            //排除不可编辑的字段
            WikiDocMetaTable::model()->update($extra['meta'], $doc_id, true);
        }

        //扩展表
        if(!empty($extra['extra'])){
            //排除不可编辑的字段
            WikiDocExtraTable::model()->update($extra['extra'], $doc_id, true);
        }

        //若原文档未删除，更新用户及标签的文档数
        if($old_doc['status'] == WikiDocsTable::STATUS_PUBLISHED &&
            isset($data['status']) && $data['status'] != WikiDocsTable::STATUS_PUBLISHED){
            //若原文档是“已发布”状态，且新状态不是“已发布”

            //用户文档数减一
            DocUserCounterService::service()->decr($old_doc['user_id']);
        }else if($old_doc['status'] != WikiDocsTable::STATUS_PUBLISHED &&
            isset($data['status']) && $data['status'] == WikiDocsTable::STATUS_PUBLISHED){
            //若原文档不是“已发布”状态，且新状态是“已发布”

            //用户文档数加一
            DocUserCounterService::service()->incr($old_doc['user_id']);
        }

        //附加属性
        if(isset($extra['props'])){
            DocPropService::service()->updatePropSet(
                $doc_id,
                $extra['props']['data'],
                isset($extra['props']['labels']) ? $extra['props']['labels'] : array()
            );
        }

        //触发事件
        \F::event()->trigger(self::EVENT_UPDATED, array(
            'doc_id'=>$doc_id,
            'old_status'=>$old_doc['status'],
        ));

        //记录历史
        DocHistoryService::service()->create($doc_id);

        return true;
    }
    
    public function delete($doc_id){
        $doc = WikiDocsTable::model()->find($doc_id, 'user_id,delete_time,status');
        if(!$doc || $doc['delete_time']){
            return false;
        }

        //标记为已删除
        WikiDocsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $doc_id);

        //若被删除文章是“已发布”状态
        if($doc['status'] == WikiDocsTable::STATUS_PUBLISHED){
            //用户文章数减一
            DocUserCounterService::service()->decr($doc['user_id']);

            //相关分类文章数减一
            DocCategoryService::service()->decr($doc_id);
        }

        //触发事件
        \F::event()->trigger(self::EVENT_DELETED, array($doc_id));

        return true;
    }
    
    public function undelete($doc_id){
        $doc = WikiDocsTable::model()->find($doc_id, 'user_id,delete_time,status');
        if(!$doc || !$doc['delete_time']){
            return false;
        }

        //标记为未删除
        WikiDocsTable::model()->update(array(
            'delete_time'=>0
        ), $doc_id);

        //若被还原文章是“已发布”状态
        if($doc['status'] == WikiDocsTable::STATUS_PUBLISHED){
            //用户文章数加一
            DocUserCounterService::service()->incr($doc['user_id']);

            //相关分类文章数加一
            DocCategoryService::service()->incr($doc_id);
        }

        //触发事件
        \F::event()->trigger(self::EVENT_UNDELETE, array($doc_id));

        return true;
    }

    /**
     * 物理删除一篇文档
     * @param int $doc_id
     * @return bool
     */
    public function remove($doc_id){
        //获取文档删除状态
        $doc = WikiDocsTable::model()->find($doc_id, 'user_id,delete_time,status');
        if(!$doc){
            return false;
        }

        //触发事件
        \F::event()->trigger(self::EVENT_REMOVING, $doc_id);

        //删除文档
        WikiDocsTable::model()->delete($doc_id);

        //若文档未通过回收站被直接删除，且文档“已发布”
        if(!$doc['delete_time'] && $doc['status'] == WikiDocsTable::STATUS_PUBLISHED){
            //则作者文档数减一
            DocUserCounterService::service()->decr($doc['user_id']);

            //分类文档数减一
            DocCategoryService::service()->decr($doc_id);
        }

        //删除文档可能存在的自定义属性
        WikiDocPropIntTable::model()->delete('relation_id = '.$doc_id);
        WikiDocPropVarcharTable::model()->delete('relation_id = '.$doc_id);
        WikiDocPropTextTable::model()->delete('relation_id = '.$doc_id);

        //删除关注，收藏列表
        WikiDocLikesTable::model()->delete('doc_id = '.$doc_id);
        WikiDocFavoritesTable::model()->delete('doc_id = '.$doc_id);

        //删除文档meta信息
        WikiDocMetaTable::model()->delete('doc_id = ' . $doc_id);

        //删除文档扩展信息
        WikiDocExtraTable::model()->delete('doc_id = ' . $doc_id);

        //删除文档历史
        WikiDocPropHistoriesTable::model()->delete('doc_id = ' . $doc_id);
        WikiDocHistoriesTable::model()->delete('doc_id = ' . $doc_id);

        return true;
    }

    /**
     * 返回一篇文档信息
     * @param int $doc_id 文档ID
     * @param string|array $fields 可指定返回字段
     *  - doc.*系列可指定docs表返回字段，若有一项为'doc.*'，则返回所有字段
     *  - meta.*系列可指定doc_meta表返回字段，若有一项为'meta.*'，则返回所有字段
     *  - props.*系列可指定返回哪些文档分类属性，若有一项为'props.*'，则返回所有文档分类属性
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param bool $only_published 若为true，则只在已发布的文档里搜索。默认为true
     * @return array|bool
     */
    public function get($doc_id, $fields = '*', $only_published = true){
        //解析$fields
        $fields = FieldHelper::parse($fields, 'doc', self::$public_fields);
        if(empty($fields['doc'])){
            //若未指定返回字段，返回所有允许的字段
            $fields['doc'] = self::$default_fields['doc'];
        }

        $doc_fields = $fields['doc']['fields'];
        if(!empty($fields['user']) && !in_array('user_id', $doc_fields)){
            //如果要获取作者信息，则必须搜出user_id
            $doc_fields[] = 'user_id';
        }
        if(!empty($fields['category']) && !in_array('cat_id', $doc_fields)){
            //如果要获取分类信息，则必须搜出cat_id
            $doc_fields[] = 'cat_id';
        }

        if(!empty($fields['props']) && !in_array('cat_id', $doc_fields)){
            //如果要获取附加属性，必须搜出cat_id
            $doc_fields[] = 'cat_id';
        }

        if(isset($fields['extra']) && in_array('seo_title', $fields['extra']) && !in_array('title', $doc_fields)){
            //如果要获取seo_title，必须搜出title
            $doc_fields[] = 'title';
        }
        if(isset($fields['extra']) && in_array('seo_keywords', $fields['extra']) && !in_array('title', $doc_fields)){
            //如果要获取seo_title，必须搜出title
            $doc_fields[] = 'title';
        }
        if(isset($fields['extra']) && in_array('seo_description', $fields['extra'])){
            //如果要获取seo_title，必须搜出title, content
            if(!in_array('abstract', $doc_fields)){
                $doc_fields[] = 'abstract';
            }
        }

        $sql = new Sql();
        $sql->from(array('d'=>WikiDocsTable::model()->getTableName()), $doc_fields)
            ->where(array(
                'd.id = ?'=>$doc_id,
            ));

        //仅搜索已发布的文档
        if($only_published){
            $sql->where(WikiDocsTable::getPublishedConditions('d'));
        }

        $doc = $sql->fetchRow();
        if(!$doc){
            return false;
        }

        if(isset($doc['thumbnail'])){
            //如果有缩略图，将缩略图图片ID转为图片对象
            $doc['thumbnail'] = $this->formatThumbnail(
                $doc['thumbnail'],
                isset($fields['doc']['extra']['thumbnail']) ? $fields['doc']['extra']['thumbnail'] : ''
            );
        }

        $return = array(
            'doc'=>$doc,
        );

        //meta
        if(!empty($fields['meta'])){
            $return['meta'] = DocMetaService::service()->get($doc_id, $fields['meta']);
        }

        //扩展信息
        if(!empty($fields['extra'])){
            $return['extra'] = DocExtraService::service()->get($doc_id, $fields['extra']);
        }

        //设置一下SEO信息
        if(isset($fields['extra']) && in_array('seo_title', $fields['extra']['fields']) && empty($return['extra']['seo_title'])){
            $return['extra']['seo_title'] = $doc['title'];
        }
        if(isset($fields['extra']) && in_array('seo_keywords', $fields['extra']['fields']) && empty($return['extra']['seo_keywords'])){
            $return['extra']['seo_keywords'] = str_replace(array(
                ' ', '|', '，'
            ), ',', $doc['title']);
        }
        if(isset($fields['extra']) && in_array('seo_description', $fields['extra']['fields']) && empty($return['extra']['seo_description'])){
            $return['extra']['seo_description'] = mb_substr(trim($doc['abstract']), 0, 150, 'utf-8');
        }

        //作者信息
        if(!empty($fields['user'])){
            $return['user'] = UserService::service()->get($doc['user_id'], $fields['user']);
        }

        //附加属性
        if(!empty($fields['props'])){
            if(in_array('*', $fields['props']['fields'])){
                $props = null;
            }else{
                $props = PropService::service()->mget($fields['props']['fields'], PropsTable::USAGE_WIKI_DOC);
            }
            $return['props'] = DocPropService::service()->getPropSet($doc_id, $props);
        }

        //主分类
        if(!empty($fields['category'])){
            $return['category'] = CategoryService::service()->get($doc['cat_id'], $fields['category']);
        }

        //过滤掉那些未指定返回，但出于某些原因先搜出来的字段
        foreach(array('user_id', 'cat_id', 'title', 'abstract') as $f){
            if(!in_array($f, $fields['doc']['fields']) && in_array($f, $doc_fields)){
                unset($return['doc'][$f]);
            }
        }

        return $return;
    }
    
    public function mget($doc_ids, $fields = '*'){
        
    }
    
    public static function isDocIdExist($doc_id){
        if($doc_id){
            $doc = WikiDocsTable::model()->find($doc_id, 'delete_time,status');
            return !($doc['delete_time'] || $doc['status'] != WikiDocsTable::STATUS_PUBLISHED);
        }else{
            return false;
        }
    }

    /**
     * 根据文档状态获取文档数
     * @param int $status 文档状态
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = WikiDocsTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }

    /**
     * 获取已删除的文档数
     * @return string
     */
    public function getDeletedCount(){
        $result = WikiDocsTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }

    private function formatThumbnail($thumbnail, $extra = ''){
        //如果有缩略图，将缩略图图片ID转为图片对象
        if(preg_match('/^(\d+)x(\d+)$/', $extra, $avatar_params)){
            return FileService::get($thumbnail, array(
                'spare'=>'doc',
                'dw'=>$avatar_params[1],
                'dh'=>$avatar_params[2],
            ));
        }else{
            return FileService::get($thumbnail, array(
                'spare'=>'doc',
            ));
        }
    }
}