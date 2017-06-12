<?php
namespace cms\widgets\select_posts\controllers;

use cms\helpers\LinkHelper;
use cms\services\post\PostService;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldsHelper;
use fay\widget\Widget;
use fay\helpers\DateHelper;

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
    
    public function initConfig($config){
        empty($config['date_format']) && $config['date_format'] = 'pretty';
        isset($config['fields']) || $config['fields'] = array('category');
        empty($config['posts']) && $config['posts'] = array();
        
        return $this->config = $config;
    }
    
    public function getData(){
        if(!$this->config['posts']){
            return array();
        }

        $fields = $this->getFields();

        $posts = $this->config['posts'];

        //排除已过期或未开始文章
        foreach($posts as $k => $p){
            if((!empty($p['start_time']) && \F::app()->current_time < $p['start_time']) ||
                (!empty($p['end_time']) && \F::app()->current_time > $p['end_time'])){
                unset($posts[$k]);
            }
        }
        
        //通过文章ID，获取文章信息结构
        $posts = PostService::service()->mget(ArrayHelper::column($posts, 'post_id'), $fields, true);
        
        //格式化返回数据结构
        return $this->formatPosts($posts);
    }
    
    public function index(){
        $posts = $this->getData();
    
        $this->renderTemplate(array(
            'posts'=>$posts,
        ));
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
            
            //附加文章链接
            $p['post']['link'] = LinkHelper::getPostLink($p['post']);
        }
        
        return $posts;
    }
}