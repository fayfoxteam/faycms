<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use cms\models\tables\CategoriesTable;
use cms\models\tables\PagesTable;
use cms\models\tables\PostsTable;
use cms\services\OptionService;
use fay\core\Sql;
use fay\helpers\ArrayHelper;

/**
 * 访问统计
 */
class SiteMapController extends ApiController{
    public function xml(){
        //系统配置
        $config = OptionService::getGroup('system:sitemap');
        $config = array_merge(array(
            'index:changefreq' => 'always',
            'index:priority' => '',
            'index:lastmod' => 'now',
            'cat:changefreq' => '',
            'cat:priority' => '',
            'cat:lastmod' => 'last_post',
            'post:changefreq' => '',
            'post:priority' => '',
            'post:lastmod' => 'update_time',
            'post_count' => '500',
        ), $config);
        $this->view->config = $config;
        
        //最后一篇文章
        $this->view->last_post = PostsTable::model()->fetchRow(PostsTable::getPublishedConditions(), 'update_time', 'id DESC');
        
        //每个分类的最后一篇文章
        $sql = new Sql();
        $cat_last_post = $sql->from(PostsTable::model()->getTableName(), 'cat_id,MAX(update_time) AS lastmod')
            ->group('cat_id')
            ->fetchAll();
        $this->view->cat_last_post = $cat_last_post;
        
        //获取有文章的分类信息
        if($cat_last_post){
            $cats = CategoriesTable::model()->fetchAll(array(
                'id IN (?)'=>ArrayHelper::column($cat_last_post, 'cat_id')
            ), 'id,alias,title');
            $this->view->cat_map = ArrayHelper::column($cats, null, 'id');
        }else{
            $this->view->cat_map = array();
        }
        
        //获取最新500篇文章
        $posts = PostsTable::model()->fetchAll(
            PostsTable::getPublishedConditions(),
            'id,cat_id,title,publish_time,update_time',
            'id DESC',
            $config['post_count']
        );
        $this->view->posts = $posts;
        
        header('Content-Type:application/xml');
        $this->config->set('debug', false);
        
        return $this->view->render();
    }
    
    public function html(){
        //最后一篇文章
        $this->view->last_post = PostsTable::model()->fetchRow(PostsTable::getPublishedConditions(), 'update_time', 'id DESC');
        
        //首页SEO标题
        $this->view->seo_title = OptionService::get('site:seo_index_title');
        $this->view->sitename = OptionService::get('site:sitename');

        //获取最新500篇文章
        $posts = PostsTable::model()->fetchAll(
            PostsTable::getPublishedConditions(),
            'id,cat_id,title,publish_time,update_time',
            'id DESC',
            OptionService::get('system:sitemap:post_count')
        );
        //根据分类将得到的文章分组
        $cat_posts = array();
        foreach($posts as $post){
            $cat_posts[$post['cat_id']][] = $post;
        }
        $this->view->cat_posts = $cat_posts;

        //获取有文章的分类信息
        if($cat_posts){
            $cats = CategoriesTable::model()->fetchAll(array(
                'id IN (?)'=>array_keys($cat_posts)
            ), 'id,alias,title');
            $this->view->cat_map = ArrayHelper::column($cats, null, 'id');
        }else{
            $this->view->cat_map = array();
        }
        
        //静态页
        $this->view->pages = PagesTable::model()->fetchAll(array(
            'status'=>PagesTable::STATUS_PUBLISHED,
            'delete_time = 0',
        ), 'id,alias,title');
        
        return $this->view->render();
    }
}