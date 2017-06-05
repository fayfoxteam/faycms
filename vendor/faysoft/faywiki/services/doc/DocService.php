<?php
namespace faywiki\services\doc;

use cms\services\CategoryService;
use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\RequestHelper;
use faywiki\models\tables\WikiDocExtraTable;
use faywiki\models\tables\WikiDocMetaTable;
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
        'nav'=>array(
            'id', 'title',
        ),
        'tags'=>array(
            'id', 'title',
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
        

        //触发事件
        \F::event()->trigger(self::EVENT_CREATED, $doc_id);

        return $doc_id;
    }
    
    public function update(){
        
    }
    
    public function delete(){
        
    }
    
    public function remove($doc_id){
        
    }
    
    public function get($doc_id, $fields = '*'){
        
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
}