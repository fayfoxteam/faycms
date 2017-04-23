<?php
namespace cms\widgets\posts\controllers;

use cms\helpers\LinkHelper;
use fay\widget\Widget;
use fay\helpers\DateHelper;

class IndexController extends Widget{
    /**
     * 返回字段
     */
    private $fields = array(
        'post'=>array(
            'fields'=>array(
                'id', 'cat_id', 'title', 'publish_time', 'user_id', 'is_top', 'thumbnail', 'abstract'
            )
        ),
        'user'=>array(
            'fields'=>array(
                'id', 'username', 'nickname', 'avatar'
            )
        ),
        'meta'=>array(
            'fields'=>array(
                'comments', 'views', 'likes'
            )
        ),
        'files'=>array(
            'fields'=>array(
                'id', 'description', 'url', 'thumbnail', 'is_image'
            )
        ),
        'category'=>array(
            'fields'=>array(
                'id', 'title', 'alias'
            )
        ),
        'tags'=>array(
            'fields'=>array(
                'id', 'title',
            )
        ),
    );
    
    public function initConfig($config){
        empty($config['page_size']) && $config['page_size'] = 10;
        empty($config['page_key']) && $config['page_key'] = 'page';
        empty($config['date_format']) && $config['date_format'] = 'pretty';
        isset($config['fields']) || $config['fields'] = array('category');
        empty($config['pager']) && $config['pager'] = 'system';
        empty($config['pager_template']) && $config['pager_template'] = '';
        empty($config['empty_text']) && $config['empty_text'] = '无相关记录！';
        isset($config['subclassification']) || $config['subclassification'] = true;
        
        return $this->config = $config;
    }
    
    public function getData(){
        
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
     * 获取$fields
     * @return array
     */
    private function getFields(){
        $fields = array(
            'post'=>$this->fields['post']
        );
        
        //文章缩略图
        if(!empty($this->config['post_thumbnail_width']) || !empty($this->config['post_thumbnail_height'])){
            $fields['post']['extra'] = array(
                'thumbnail'=>(empty($this->config['post_thumbnail_width']) ? 0 : $this->config['post_thumbnail_width']) .
                    'x' .
                    (empty($this->config['post_thumbnail_height']) ? 0 : $this->config['post_thumbnail_height']),
            );
        }
        //分类信息
        if(in_array('category', $this->config['fields'])){
            $fields['category'] = $this->fields['category'];
        }
        //计数器
        if(in_array('meta', $this->config['fields'])){
            $fields['meta'] = $this->fields['meta'];
        }
        //用户信息
        if(in_array('user', $this->config['fields'])){
            $fields['user'] = $this->fields['user'];
        }
        //标签
        if(in_array('tags', $this->config['fields'])){
            $fields['tags'] = $this->fields['tags'];
        }
        //附加属性
        if(in_array('props', $this->config['fields'])){
            $fields['props'] = array(
                'fields'=>array(
                    '*'
                )
            );
        }
        //附件缩略图
        if(in_array('files', $this->config['fields'])){
            $file_fields = $this->fields['files'];
            if(!empty($this->config['file_thumbnail_width']) || !empty($this->config['file_thumbnail_height'])){
                $file_fields['extra'] = array(
                    'thumbnail'=>(empty($this->config['file_thumbnail_width']) ? 0 : $this->config['file_thumbnail_width']) .
                        'x' .
                        (empty($this->config['file_thumbnail_height']) ? 0 : $this->config['file_thumbnail_height']),
                );
            }
            $fields['files'] = $file_fields;
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