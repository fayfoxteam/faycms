<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;
use faywiki\models\tables\WikiDocsTable;

class DocService extends Service{
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
        'post'=>array(
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
    
    public function create(){
        
    }
    
    public function update(){
        
    }
    
    public function delete(){
        
    }
    
    public function get($doc_id, $fields = '*'){
        
    }
    
    public function mget($doc_ids, $fields = '*'){
        
    }
    
    public static function isDocIdExist($doc_id){
        if($doc_id){
            $post = WikiDocsTable::model()->find($doc_id, 'delete_time,status');
            return !($post['delete_time'] || $post['status'] != WikiDocsTable::STATUS_PUBLISHED);
        }else{
            return false;
        }
    }
}