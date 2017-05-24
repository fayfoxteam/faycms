<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use cms\library\Db;
use cms\models\tables\PostsTable;
use cms\services\file\FileException;
use cms\services\file\FileService;
use cms\services\post\PostService;
use fay\core\db\Exception;

class MigrationController extends FrontController{
    public function doAction(){
        $offset = $this->input->get('offset', 'intval', 0);
        $archives = Db::getInstance()->fetchAll("SELECT * FROM dede_archives WHERE  id > {$offset} ORDER BY id LIMIT 1");
        
        foreach($archives as $archive){
            if($archive['litpic']){
                //采集图片
                try{
                    $file = FileService::service()->uploadFromUrl('http://news.22.cn' . $archive['litpic'], 201);
                    $thumbnail = $file['id'];
                }catch(FileException $e){
                    //如果获取远程图片失败，就跳过
                    $thumbnail = 0;
                } 
            }else{
                $thumbnail = 0;
            }
            
            //处理富文本中的图片
            $addon = Db::getInstance()->fetchRow("SELECT * FROM dede_addonarticle WHERE aid = {$archive['id']}");
            if($addon){
                $content = $addon['body'];
            }else{
                //没有内容的文章直接跳过
                continue;
            }
            preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $content, $matches);
            if(isset($matches[1])){
                foreach($matches[1] as $match){
                    try{
                        if(substr($match, 0, 4) == 'http'){
                            $file = FileService::service()->uploadFromUrl($match, 201);
                        }else{
                            $file = FileService::service()->uploadFromUrl('http://news.22.cn' . $match, 201);
                        }
                        
                        if($file){
                            $content = str_replace($match, $file['url'], $content);
                        }

                        unset($file);
                    }catch(FileException $e){
                        //如果获取远程图片失败，就跳过
                    }
                }
            }
            
            //获取对应分类ID
            $cat_map = array(
                '1'=>10000,
                '65'=>10001,
                '4'=>10002,
                '2'=>10003,
                '3'=>10004,
            );
            if(!isset($cat_map[$archive['typeid']])){
                //分类都对应不到的直接跳过算了
                continue;
            }
            $cat_id = $cat_map[$archive['typeid']];
            
            //确定状态
            if($archive['arcrank'] == '0'){
                $status = PostsTable::STATUS_PUBLISHED;
            }else{
                //不是开放浏览统一为草稿
                $status = PostsTable::STATUS_DRAFT;
            }
            
            PostService::service()->create(array(
                'id'=>$archive['id'],
                'title'=>$archive['title'],
                'content'=>$content,
                'publish_time'=>$archive['pubdate'],
                'thumbnail'=>$thumbnail,
                'cat_id'=>$cat_id,
                'status'=>$status,
                'abstract'=>$archive['description'],
            ), array(
                'extra'=>array(
                    'seo_keywords'=>$archive['keywords'],
                    'seo_description'=>$archive['description'],
                )
            ), 10000);
        }
        
        if(isset($archive['id'])){
            echo $archive['id'];
        }else{
            echo '没有啦';
        }
    }

}