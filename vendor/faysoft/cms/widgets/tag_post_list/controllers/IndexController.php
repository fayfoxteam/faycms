<?php
namespace cms\widgets\tag_post_list\controllers;

use cms\helpers\LinkHelper;
use fay\helpers\ArrayHelper;
use cms\models\tables\TagsTable;
use cms\services\post\PostService;
use fay\helpers\FieldsHelper;
use fay\widget\Widget;
use fay\core\Sql;
use fay\common\ListView;
use cms\models\tables\PostsTable;
use cms\services\CategoryService;
use fay\helpers\DateHelper;
use fay\core\HttpException;

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
        empty($config['tag_title_key']) && $config['tag_title_key'] = 'tag_title';
        empty($config['tag_id_key']) && $config['tag_id_key'] = 'tag_id';
        empty($config['page_size']) && $config['page_size'] = 10;
        empty($config['page_key']) && $config['page_key'] = 'page';
        empty($config['date_format']) && $config['date_format'] = 'pretty';
        isset($config['fields']) || $config['fields'] = array('category');
        empty($config['pager']) && $config['pager'] = 'system';
        empty($config['pager_template']) && $config['pager_template'] = '';
        empty($config['empty_text']) && $config['empty_text'] = '无相关记录！';
        empty($config['cat_id']) && $config['cat_id'] = 0;
        
        return $this->config = $config;
    }
    
    public function getData(){
        $listview = $this->getListView();
        //获取符合条件的文章ID
        $posts = $listview->getData();
        
        if($posts){
            $fields = $this->getFields();
            //通过文章ID，获取文章信息结构
            $posts = PostService::service()->mget(ArrayHelper::column($posts, 'post_id'), $fields);
            //格式化返回数据结构
            $posts = $this->formatPosts($posts);
        }
        
        return array(
            'data'=>$posts,
            'pager'=>$listview->getPager(),
        );
    }
    
    public function index(){
        $listview = $this->getListView();
        //获取符合条件的文章ID
        $posts = $listview->getData();
        
        if($posts){
            $fields = $this->getFields();
            //通过文章ID，获取文章信息结构
            $posts = PostService::service()->mget(ArrayHelper::column($posts, 'post_id'), $fields);
            //格式化返回数据结构
            $posts = $this->formatPosts($posts);
            
            $this->renderTemplate(array(
                'posts'=>$posts,
            ));
        }else{
            echo $this->config['empty_text'];
        }
        
        if($this->config['pager'] == 'system'){
            $listview->showPager();
        }else{
            $pager_data = $listview->getPager();
            if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['pager_template'])){
                $listview->setPagerView($this->config['pager_template'])
                    ->showPager();
            }else{
                \F::app()->view->evalCode($this->config['pager_template'], array(
                    'widget'=>$this
                ) + $pager_data);
            }
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
        if(!empty($this->config['file_thumbnail_width']) || !empty($this->config['file_thumbnail_height'])){
            $fields->files->addExtra('thumbnail', (empty($this->config['file_thumbnail_width']) ? 0 : $this->config['file_thumbnail_width']) .
                'x' .
                (empty($this->config['file_thumbnail_height']) ? 0 : $this->config['file_thumbnail_height']));
        }
        
        return $fields;
    }
    
    /**
     * 获取ListView对象
     * @return ListView
     * @throws HttpException
     * @throws \fay\core\ErrorException
     */
    private function getListView(){
        //获取标签
        if(!empty($this->config['tag_id_key']) && $this->input->get($this->config['tag_id_key'])){
            $tag_id = $this->input->get($this->config['tag_id_key'], 'intval');
        }else if(!empty($this->config['tag_title_key']) && $this->input->get($this->config['tag_title_key'])){
            $tag = TagsTable::model()->fetchRow(array(
                'title = ?'=>$this->input->get($this->config['tag_title_key'])
            ), 'id');
            if(!$tag){
                throw new HttpException('您访问的页面不存在');
            }
            $tag_id = $tag['id'];
        }else{
            $tag_id = isset($this->config['tag_id']) ? $this->config['tag_id'] : 0;
        }
        
        if(empty($tag_id)){
            throw new HttpException('您访问的页面不存在');
        }
        
        $sql = new Sql();
        $sql->from(array('pt'=>'posts_tags'), 'post_id')
            ->joinLeft(array('p'=>'posts'), 'pt.post_id = p.id')
            ->where('tag_id = ?', $tag_id)
            ->where(PostsTable::getPublishedConditions('p'))
            ->order($this->getOrder())
        ;
        if($this->config['cat_id']){
            $cat_ids = CategoryService::service()->getChildIds($this->config['cat_id']);
            $cat_ids[] = $this->config['cat_id'];
            $sql->where('p.cat_id IN (?)', $cat_ids);
        }
        
        $listview = new ListView($sql, array(
            'page_size'=>$this->config['page_size'],
            'page_key'=>$this->config['page_key'],
            'empty_text'=>$this->config['empty_text'],
        ));
        
        return $listview;
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