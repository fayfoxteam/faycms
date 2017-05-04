<?php
namespace cms\services\file;

use cms\models\tables\FilesTable;
use cms\services\CategoryService;
use fay\helpers\LocalFileHelper;
use fay\helpers\ImageHelper;
use fay\helpers\UrlHelper;

/**
 * 远程文件处理
 */
class RemoteFileService{
    /**
     * @var string 远程链接地址
     */
    private $url;
    
    /**
     * @var string 存储文件扩展名（别忘了前面的点）
     * 若不指定，系统会根据url和header信息猜测一个
     */
    private $ext_name;
    
    /**
     * @var string 若设置了user agent，请求远程文件时会带上这个值
     */
    private $user_agent;
    
    /**
     * @var string 若设置了referer，请求远程文件时会带上这个值
     */
    private $referer;
    
    /**
     * @var string curl返回的http头
     */
    private $header;
    
    /**
     * @var array curl返回的http头解析为数组。键名会转为小写存储，避免大小写问题导致找不到属性
     */
    private $header_map = array();
    
    /**
     * @var string curl返回的远程文件
     */
    private $body;
    
    /**
     * @var bool 是否已下载
     */
    private $is_download = false;
    
    public function __construct($url){
        $this->url = $url;
    }
    
    /**
     * 保存为本地文件
     * @param int $cat 文件分类
     * @param bool $only_image 若为true，则不保存无法识别为图片的文件（可能有些确实是图，只是无法识别）
     * @param string $client_name 客户端名称，文件从客户端上传的时候有这个字段，有时候会作为图片alt属性显示，留空则默认为url
     * @return array
     * @throws FileErrorException
     * @throws FileException
     */
    public function save($cat = 0, $only_image = true, $client_name = ''){
        if($cat){
            if(!is_array($cat)){
                $cat = CategoryService::service()->get($cat, 'id,alias', '_system_file');
            }
        
            if(!$cat){
                throw new FileErrorException('cms\services\file\FileService::upload传入$cat不存在');
            }
        }else{
            $cat = array(
                'id'=>0,
                'alias'=>'',
            );
        }
        
        if($this->isImageByHeader()){//这个判断其实隐含了下载的过程
            $file = @imagecreatefromstring($this->body);
            if(!$file){
                throw new FileException('无法将远程文件转换为图片');
            }
        }
        if($only_image && empty($file)){
            return array();
        }
        
        //确定存储目录
        $target = $cat['alias'] ? $cat['alias'] . '/' : '';
        $upload_path = './uploads/' . APPLICATION . '/' . $target . date('Y/m/');
        //若指定目录不存在，则创建目录
        $ext_name = $this->getExtName();
        LocalFileHelper::createFolder($upload_path);
        $filename = FileService::getFileName($upload_path, $ext_name);
        if(defined('NO_REWRITE')){
            $destination = './public/'.$upload_path . $filename;
        }else{
            $destination = $upload_path . $filename;
        }
    
        //存储原图
        file_put_contents($destination, $this->body);
    
        $data = array(
            'raw_name'=>substr($filename, 0, 0 - strlen($ext_name)),
            'file_ext'=>$ext_name,
            'file_type'=>$this->getHeader('content-type'),
            'file_size'=>filesize($destination),
            'file_path'=>$upload_path,
            'client_name'=>$client_name ? $client_name : $this->url,
            'is_image'=>empty($file) ? 0 : 1,
            'image_width'=>empty($file) ? 0 : imagesx($file),
            'image_height'=>empty($file) ? 0 : imagesy($file),
            'upload_time'=>\F::app()->current_time,
            'user_id'=>\F::app()->current_user,
            'cat_id'=>$cat['id'],
        );
        $data['id'] = FilesTable::model()->insert($data);
        if(!empty($file)){
            ImageHelper::output(
                ImageHelper::resize($file, 100, 100),
                $data['file_type'],
                (defined('NO_REWRITE') ? './public/' : '').$data['file_path'].$data['raw_name'].'-100x100'.$data['file_ext']
            );
        }
        
        //公共文件直接给出真实路径
        $data['url'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
        $data['thumbnail'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . '-100x100.jpg';
        //真实存放路径（是图片的话与url路径相同）
        $data['src'] = UrlHelper::createUrl() . ltrim($data['file_path'], './') . $data['raw_name'] . $data['file_ext'];
        
        return $data;
    }
    
    /**
     * curl获取远程图片到内存
     */
    public function download(){
        if($this->is_download){
            //防止重复下载
            return true;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true); //取得返回头信息
        if($this->user_agent){
            //带上UA
            curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        }
        if($this->referer){
            //带上referer（有的远程可能会有防盗链之类的措施，需要伪造referer）
            curl_setopt ($ch, CURLOPT_REFERER, $this->referer);
        }
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
    
        if($http_code != 200){
            throw new FileException('获取远程文件失败，状态码：' . $http_code);
        }
    
        $this->header = substr($response, 0, $header_size);
        $this->body = substr($response, $header_size);
        
        $this->is_download = true;
        return true;
    }
    
    /**
     * 通过header判断远程文件是否为图片
     */
    public function isImageByHeader(){
        return in_array($this->getHeader('content-type'), array(
            'image/pjpeg', 'image/jpeg', 'image/gif', 'image/png', 'image/xpng', 'image/wbmp', 'image/webp'
        ));
    }
    
    /**
     * 根据关键词获取http头属性值
     * @param string $key
     * @return string|null
     */
    public function getHeader($key){
        $key = strtolower($key);//转为小写
        if(!$this->is_download){
            //若为下载，先下载文件到内存
            $this->download();
        }
        
        if(!$this->header_map){
            //将header解析为数组
            $this->parseHeader();
        }
        
        return isset($this->header_map[$key]) ? $this->header_map[$key] : null;
    }
    
    /**
     * 将curl返回的http头解析为键值对数组
     */
    private function parseHeader(){
        $header_arr = explode("\n", $this->header);
        foreach($header_arr as $ha){
            $ha_arr = explode(':', $ha, 2);
            //对于那些不是key: value的header，不做解析
            if(count($ha_arr) == 2){
                $this->header_map[strtolower(trim($ha_arr[0]))] = trim($ha_arr[1]);
            }
        }
    }
    
    /**
     * 猜测出一个扩展名
     */
    private function getExtName(){
        if($this->ext_name !== null){
            //若指定了扩展名，直接返回
            return $this->ext_name;
        }
        
        //如果链接自带了扩展名，就用自带的
        $url_parts = parse_url($this->url);
        if(!empty($url_parts['path'])){
            $path_parts = pathinfo($url_parts['path']);
            if(isset($path_parts['extension'])){
                //这样取得的扩展名，可能包含参数或者锚点之类的东西，截取文本部分
                preg_match('/[a-zA-Z]+/', $path_parts['extension'], $match);
                return '.' . strtolower($match[0]);
            }
        }
        
        //若链接中不包含扩展名，通过Content Type猜测扩展名
        $content_type = $this->getHeader('content-type');
        //text/html; charset=UTF-8 类似这种，把后面的编码方式去掉
        preg_match('/[^; ]+/', $content_type, $match);
        $clean_content_type = strtolower(trim($match[0]));
        $mimes = \F::config()->getFile('mimes');
        foreach($mimes as $key => $mime){
            if(is_array($mime)){
                foreach($mime as $m){
                    if($m == $clean_content_type){
                        return '.' . $key;
                    }
                }
            }else{
                if($mime == $clean_content_type){
                    return '.' . $key;
                }
            }
        }
        
        //实在是确定不了扩展名，就返回空
        return '';
    }
    
    /**
     * @return string
     */
    public function getUserAgent(){
        return $this->user_agent;
    }
    
    /**
     * @param string $user_agent
     * @return $this
     */
    public function setUserAgent($user_agent){
        $this->user_agent = $user_agent;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getReferer(){
        return $this->referer;
    }
    
    /**
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer){
        $this->referer = $referer;
        return $this;
    }
    
    /**
     * @param string $ext_name
     * @return $this
     */
    public function setExtName($ext_name){
        $this->ext_name = $ext_name;
        return $this;
    }
}