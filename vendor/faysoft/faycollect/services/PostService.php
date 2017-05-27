<?php
namespace faycollect\services;

use cms\services\file\RemoteFileService;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\StringHelper;
use fay\validators\UrlValidator;

/**
 * 采集文章服务
 */
class PostService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 创建文章
     * @param array $post 包含文章信息的数组
     * @param array $extra 扩展信息
     * @param bool $auto_thumbnail
     * @param bool $download_remote_image
     * @return int
     */
    public function create(array $post, array $extra, $auto_thumbnail = true, $download_remote_image = true){
        //若标题长度大于500，直接截取
        $post['title'] = StringHelper::niceShort($post['title'], 500);
        
        //若未指定缩略图，且设置了获取正文第一张图片为缩略图，尝试提取图片
        $thumbnail = $this->formatThumbnail($post['thumbnail'], $auto_thumbnail, $post['content']);
        $local_thumbnail = $this->downloadThumbnail($thumbnail);
        
        //将发布时间格式化为时间戳
        $post['publish_time'] = $this->formatPublishTime($post['publish_time']);
        
        if($download_remote_image){
            $post['content'] = $this->downloadRemoteImages($post['content'], $thumbnail, $local_thumbnail);
        }
        
        //标签
        $tags = $this->formatTags($extra['tags']);
        
        //自动生成摘要
        if(empty($post['abstract'])){
            $post['abstract'] = mb_substr(trim(str_replace('　', '', strip_tags($post['content']))), 0, 200, 'UTF-8');
        }
        
        return \cms\services\post\PostService::service()->create(array(
            'title' => $post['title'],
            'content' => $post['content'],
            'cat_id' => $post['cat_id'],
            'thumbnail' => empty($local_thumbnail['id']) ? 0 : $local_thumbnail['id'],
            'status' => $post['status'],
            'publish_time' => $post['publish_time'],
            'abstract'=>$post['abstract'],
        ), array(
            'tags'=>$tags,
            'extra'=>array(
                'seo_title'=>empty($extra['extra']['seo_title']) ? $post['title'] : $extra['extra']['seo_title'],
                'seo_keywords'=>empty($extra['extra']['seo_keywords']) ? '' : $extra['extra']['seo_keywords'],
                'seo_description'=>empty($extra['extra']['seo_description']) ? $post['abstract'] : $extra['extra']['seo_description'],
            )
        ));
    }
    
    /**
     * 将标签格式化为逗号分割
     * 替换空格，中文逗号，竖线为英文逗号
     * @param string $tags
     * @return string
     */
    private function formatTags($tags){
        return str_replace(array(
            '，', ' ', '|'
        ), ',', $tags);
    }
    
    /**
     * 尝试将发布时间改为时间戳
     * @param string $publish_time
     * @return int
     */
    private function formatPublishTime($publish_time){
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
            //若无法转换为时间戳，设置当前时间
            $publish_time = \F::app()->current_time;
        }
        
        return $publish_time;
    }
    
    /**
     * 确定缩略图
     * @param string $thumbnail
     * @param bool $auto_thumbnail 若未设置缩略图，且$auto_thumbnail为true，则尝试获取$content中第一张图片为缩略图
     * @param string $content 正文
     * @return string
     */
    private function formatThumbnail($thumbnail, $auto_thumbnail, $content){
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
        
        return $thumbnail;
    }
    
    /**
     * 将缩略图下载到本地（若未设置缩略图或缩略图链接格式异常或下载失败，返回0）
     * @param string $thumbnail
     * @return array 本地文件信息
     */
    private function downloadThumbnail($thumbnail){
        $url_validator = new UrlValidator();
        //先验证一下url格式，若格式异常，则直接返回0
        if($url_validator->validate($thumbnail) === true){
            try{
                $remote_file = new RemoteFileService($thumbnail);
                $local_thumbnail = $remote_file->save();
                return $local_thumbnail;
            }catch(\Exception $e){
                return array();
            }
        }
        
        return array();
    }
    
    /**
     * 下载正文中包含的图片，并替换图片路径为本地路径
     * @param string $content
     * @param string $thumbnail
     * @param array $local_thumbnail 本地缩略图信息数组（包含id, src等信息）
     * @return string
     */
    private function downloadRemoteImages($content, $thumbnail, $local_thumbnail){
        //将content中包含的图片都下载到本地
        preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $content, $content_images);
        foreach($content_images[1] as $ci){
            if(substr($ci, 0, 2) == '//'){
                //若以双斜杠开头，补上http
                $url = 'http:' . $ci;
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
        
        return $content;
    }
}