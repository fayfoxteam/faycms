<?php
namespace cms\widgets\select_posts\controllers;

use cms\helpers\LinkHelper;
use cms\services\post\PostService;
use fay\helpers\ArrayHelper;
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
        $posts = PostService::service()->mget(ArrayHelper::column($posts, 'post_id'), $fields);
        
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