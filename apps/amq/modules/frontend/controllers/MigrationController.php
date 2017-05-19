<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use cms\library\Db;
use cms\models\tables\PostsTable;
use cms\services\file\FileService;
use cms\services\post\PostService;
use fay\core\db\Exception;

class MigrationController extends FrontController{
    public function doAction(){
        $offset = $this->input->get('offset', 0);
        $archives = Db::getInstance()->fetchAll("SELECT * FROM dede_archives ORDER BY id LIMIT {$offset}, 10");
        
        foreach($archives as $archive){
            if($archive['litpic']){
                //采集图片
                $file = FileService::service()->uploadFromUrl('http://news.22.cn' . $archive['litpic'], 201);
                
                $thumbnail = $file['id'];
            }else{
                $thumbnail = 0;
            }
            
            //处理富文本中的图片
            $addon = Db::getInstance()->fetchRow("SELECT * FROM dede_addonarticle WHERE aid = {$archive['id']} AND typeid = 2");
            if($addon){
                $content = $addon['body'];
            }else{
                $content = '';
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
                    }catch(Exception $e){
                        //如果获取远程图片失败，就跳过
                    }
                }
            }
            
            //获取对应分类ID
            $cat_map = array(
                '1'=>10000,
                '2'=>10001,
                '3'=>10002,
                '4'=>10000,
                '9'=>10001,
                '65'=>10002,
            );
            $cat_id = $cat_map[$archive['typeid']];
            
            //确定状态
            if($archive['arcrank'] == '0'){
                $status = PostsTable::STATUS_PUBLISHED;
            }else{
                //不是开放浏览统一为草稿
                $status = PostsTable::STATUS_DRAFT;
            }
            
            PostService::service()->create(array(
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
    }

}