<?php
namespace cms\widgets\category_posts\controllers;

use cms\helpers\LinkHelper;
use cms\services\post\PostService;
use fay\helpers\FieldsHelper;
use fay\widget\Widget;
use cms\services\CategoryService;
use fay\helpers\DateHelper;
use cms\services\post\PostCategoryService;

class IndexController extends Widget{
    /**
     * 返回字段
     */
    private $fields = array(
        'post'=>array(
            'id', 'cat_id', 'title', 'publish_time', 'user_id', 'is_top', 'thumbnail', 'abstract'
        ),
        'extra'=>array(
            'source', 'source_link'
        ),
        'user'=>array(
            'id', 'username', 'nickname', 'avatar'
        ),
        'meta'=>array(
            'comments', 'views', 'likes'
        ),
        'files'=>array(
            'id', 'description', 'url', 'thumbnail', 'is_image'
        ),
        'category'=>array(
            'id', 'title', 'alias'
        ),
        'tags'=>array(
            'id', 'title',
        ),
        'props'=>array(
            '*',
        ),
    );
    
    public function getData(){
        $conditions = $this->getConditions();
        
        $posts = PostCategoryService::service()->getPosts(
            $this->getTopCategory(),
            $this->config['number'],
            $this->getFields(),
            $this->config['subclassification'],
            $this->getOrder(),
            $conditions
        );
        
        $posts = $this->formatPosts($posts);
        
        return $posts;
    }
    
    public function index(){
        $posts = $this->getData();
        
        //若无文章可显示，则不显示该widget
        if(empty($posts) && !$this->config['show_empty']){
            return;
        }
        
        $this->renderTemplate(array(
            'posts'=>$posts,
        ));
    }
    
    /**
     * 初始化配置
     * @param array $config
     * @return array
     */
    public function initConfig($config){
        //root node
        if(empty($config['cat_id'])){
            $root_node = CategoryService::service()->getByAlias('_system_post', 'id');
            $config['cat_id'] = $root_node['id'];
        }
        
        //title
        if(empty($config['title'])){
            $node = CategoryService::service()->get($config['cat_id'], 'title');
            $config['title'] = $node['title'];
        }
        
        //number
        empty($config['number']) && $config['number'] = 5;
        
        //show_empty
        isset($config['show_empty']) || $config['show_empty'] = 0;
        
        //date format
        empty($config['date_format']) && $config['date_format'] = 'pretty';
        
        //仅返回有缩略图的文章
        if(empty($config['thumbnail'])){
            $config['thumbnail'] = false;
        }else{
            $config['thumbnail'] = true;
        }
        
        isset($config['subclassification']) || $config['subclassification'] = true;
        
        isset($config['fields']) || $config['fields'] = array('meta');
        
        return $this->config = $config;
    }
    
    /**
     * 获取分类
     */
    private function getTopCategory(){
        //限制分类
        if(!empty($this->config['cat_key']) && $this->input->get($this->config['cat_key'])){
            return $this->input->get($this->config['cat_key'], 'trim');
        }else{
            return isset($this->config['cat_id']) ? $this->config['cat_id'] : 0;
        }
    }
    
    /**
     * 获取排序方式
     * @return string
     */
    private function getOrder(){
        if(!empty($this->config['order']) && isset(PostService::$sort_by[$this->config['order']])){
            return PostService::$sort_by[$this->config['order']];
        }else{
            return PostService::$sort_by['hand'];
        }
    }
    
    /**
     * 获取$fields
     * @return FieldsHelper
     */
    private function getFields(){
        $fields = array(
            'post'=>$this->fields['post'],
            'extra'=>$this->fields['extra'],
        );

        foreach($this->config['fields'] as $f){
            if(isset($this->fields[$f])){
                $fields[$f] = $this->fields[$f];
            }
        }

        $fields = new FieldsHelper($fields, 'post');
        
        //文章缩略图
        if(!empty($this->config['post_thumbnail_width']) || !empty($this->config['post_thumbnail_height'])){
            $fields->addExtra('thumbnail', (empty($this->config['post_thumbnail_width']) ? 0 : $this->config['post_thumbnail_width']) .
                'x' .
                (empty($this->config['post_thumbnail_height']) ? 0 : $this->config['post_thumbnail_height']));
        }

        //附件缩略图
        if(in_array('files', $this->config['fields'])){
            $fields->files->addExtra('thumbnail', (empty($this->config['file_thumbnail_width']) ? 0 : $this->config['file_thumbnail_width']) .
                'x' .
                (empty($this->config['file_thumbnail_height']) ? 0 : $this->config['file_thumbnail_height']));
        }
        
        return $fields;
    }
    
    /**
     * 获取附加条件
     * @return array
     */
    private function getConditions(){
        $conditions = array();
        if($this->config['thumbnail']){
            $conditions[] = 'thumbnail != 0';
        }
        
        //限制最后访问时间，防止推出很古老的热门文章
        if(!empty($this->config['last_view_time'])){
            $conditions[] = 'last_view_time > '.(\F::app()->current_time - 86400 * $this->config['last_view_time']);
        }
        
        return $conditions;
    }
    
    /**
     * @param array $posts
     * @return array
     */
    private function formatPosts($posts){
        foreach($posts as &$p){
            //附加格式化日期
            if($this->config['date_format'] == 'pretty'){
                $p['post']['format_publish_time'] = DateHelper::niceShort($p['post']['publish_time']);
            }else if($this->config['date_format']){
                $p['post']['format_publish_time'] = \date($this->config['date_format'], $p['post']['publish_time']);
            }else{
                $p['post']['format_publish_time'] = '';
            }
    
            $p['post']['link'] = LinkHelper::getPostLink($p['post']);
        }
        
        return $posts;
    }
}