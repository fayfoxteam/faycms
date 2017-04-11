<?php
namespace faycollect\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\PostsTable;
use cms\services\CategoryService;
use cms\services\file\RemoteFileService;
use cms\services\post\PostService;
use fay\helpers\RequestHelper;
use fay\helpers\StringHelper;
use fay\validators\UrlValidator;

class PostController extends AdminController{
    /**
     * 创建文章
     * 专门给采集软件用的文章入库，没有后台编辑那个控制器那么复杂，字段格式检查容错也高一些。
     */
    public function create(){
        //获取参数。不太好用表单验证机制，因为各种参数前后都可能带有不可见字符
        $title = $this->input->post('title', 'trim');//标题
        $content = $this->input->post('content');//正文
        $publish_time = $this->input->post('publish_time');//发布时间
        $cat_id = $this->input->post('cat_id', 'intval');//分类ID
        $status = $this->input->post('status', 'intval', PostsTable::STATUS_PUBLISHED);//状态，默认为已发布
        $thumbnail = $this->input->post('thumbnail', 'trim');//缩略图
        $auto_thumbnail = !!$this->input->post('auto_thumbnail', 'intval', 1);//若为true，且未指定缩略图，则尝试获取文章第一张图片作为缩略图
        $download_remote_image = !!$this->input->post('remote', 'intval', 1);//若为true，则将content中包含的图片下载到本地
        
        $cat_id || $cat_id = CategoryService::service()->getIdByAlias('_system_post');
        $cat = CategoryService::service()->get($cat_id, 'id', '_system_post');
    
        if(!$cat){
            $this->error('分类不存在');
        }
        
        if(!$title){
            $this->error('标题不能为空');
        }
        
        if(!$thumbnail && $auto_thumbnail){
            //未指定缩略图，且指定自动获取缩略图，则尝试获取正文第一张图片作为缩略图
            preg_match('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $content, $match);
            if(isset($match[1])){
                $thumbnail = $match[1];
            }
        }
        if($thumbnail && substr($thumbnail, 0, 2) == '//'){
            //若以双斜杠开头，补上http
            $thumbnail = 'http:' . $thumbnail;
        }
        
        //若标题长度大于500，直接截取
        $title = StringHelper::niceShort($title, 500);
        
        //尝试将发布时间改为时间戳
        if($publish_time){
            //先去空格
            $publish_time = trim($publish_time);
            //把年，月替换为-；年，月替换为:；日替换为空格；秒替换为空
            $publish_time = str_replace(array(
                '年', '月', '时', '分', '日', '秒'
            ), array(
                '-', '-', ':', ':', ' ', '',
            ), $publish_time);
            //前面的替换可能导致末尾出现-, :或空格
            $publish_time = trim($publish_time, '-: ');
            $publish_time = strtotime($publish_time);
        }
        if(!$publish_time){
            //若无法转换为时间戳，设置0
            $publish_time = 0;
        }
        
        //将缩略图存到本地
        if($thumbnail){
            $url_validator = new UrlValidator();
            if($url_validator->validate($thumbnail) === true){
                try{
                    $remote_file = new RemoteFileService($thumbnail);
                    $local_thumbnail = $remote_file->save();
                }catch(\Exception $e){
                    //如果无法下载，啥也不做
                }
            }
        }
        
        if($download_remote_image){
            //将content中包含的图片都下载到本地
            preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $content, $content_images);
            foreach($content_images[1] as $ci){
                if(substr($ci, 0, 2) == '//'){
                    //若以双斜杠开头，补上http
                    $url = 'http:' . $thumbnail;
                }else{
                    $url = $ci;
                }
                try{
                    if($url == $thumbnail && !empty($local_thumbnail)){
                        //若链接刚好是缩略图（可能缩略图就是从正文里取的），则不需要再下载一次了
                        $local_pic = $local_thumbnail;
                    }else{
                        $remote_file_service = new RemoteFileService($url);
                        $local_pic = $remote_file_service->save();
                    }
                    if(!empty($local_pic['src'])){
                        $content = str_replace($ci, $local_pic['src'], $content);
                    }
                }catch(\Exception $e){
                    //如果无法下载，啥也不做
                }
            }
        }
    
        PostService::service()->create(array(
            'title'=>$title,
            'content'=>$content,
            'cat_id'=>$cat['id'],
            'thumbnail'=>empty($local_thumbnail['id']) ? 0 : $local_thumbnail['id'],
            'status'=>$status,
            'publish_time'=>$publish_time,
        ), array(
            'ip_int'=>RequestHelper::ip2int($this->ip),
        ));
        
        echo '发布成功';die;
    }
    
    /**
     * 输出报错
     * @param string $message
     */
    private function error($message){
        echo $message;
        die;
    }
}