<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use cms\models\tables\PostsTable;
use cms\services\file\RemoteFileService;
use cms\services\post\PostService;
use fay\core\HttpException;

class GuanwangController extends FrontController{
    private $type_cat_map = array(
        '1'=>10000,
        '65'=>10001,
        '4'=>10002,
        '2'=>10003,
        '3'=>10004,
    );
    
    /**
     * 从官网同步过来文章
     */
    public function article_add(){
        if($this->input->request('token') != '7cb46c0fc4cc7d78aced3fa6f096457f'){
            throw new HttpException('您请求的页面不存在');
        }
        
        $title = $this->input->request('title');
        $content = $this->input->request('body');
        $typeid = $this->input->request('typeid');
        
        $cat_id = isset($this->type_cat_map[$typeid]) ? $this->type_cat_map[$typeid] : 0;

        //提取缩略图
        preg_match('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $content, $match);
        if(isset($match[1])){
            $thumbnail = $match[1];
            if(substr($thumbnail, 0, 2) == '//'){
                $thumbnail = 'http:' . $thumbnail;
            }
            $remote_file = new RemoteFileService($thumbnail);
            $local_thumbnail = $remote_file->save();
        }
        
        
//        \F::logger()->log(serialize($_POST));
//        \F::logger()->log($title);
//        \F::logger()->log($content);
//        \F::logger()->log($cat_id);
        
        $post_id = PostService::service()->create(array(
            'title'=>$title,
            'content'=>str_replace('{and}', '&', $content),
            'cat_id'=>$cat_id,
            'status'=>PostsTable::STATUS_DRAFT,
            'thumbnail'=>empty($local_thumbnail['id']) ? 0 : $local_thumbnail['id']
        ), array(
            'source'=>'爱名网',
        ), 10000);
        
        if($post_id){
            echo '发布成功啦';
        }else{
            echo '失败';
        }
    }
}