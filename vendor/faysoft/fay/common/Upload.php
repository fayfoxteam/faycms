<?php 
namespace fay\common;

use cms\services\file\FileService;
use cms\services\file\ImageService;
use fay\helpers\LocalFileHelper;

class Upload{
    private $upload_path;
    private $allowed_types = array();
    private $max_size;
    private $auto_orientate = true;//是否在上传时尝试自动识别并旋转jpg图片角度
    
    private $error_msg = array();
    
    private $file_temp;//上传文件临时文件
    private $file_size;//上传文件大小
    private $file_type;//上传文件类型
    private $file_ext;//扩展名
    private $file_name;//随机文件名
    private $client_name;//上传图片客户端文件名
    
    /**
     * 构造函数
     * 设置参数信息
     * @param array $config 未设置的选项默认读取配置文件中的设置
     */
    public function __construct($config = array()){
        $default_config = \F::config()->get('upload');
        isset($config['allowed_types']) ? $this->setAllowedTypes($config['allowed_types']) : $this->setAllowedTypes($default_config['allowed_types']);
        $this->upload_path = isset($config['upload_path']) ? $config['upload_path'] : $default_config['upload_path'];
        $this->max_size = isset($config['max_size']) ? $config['max_size'] : $default_config['max_size'];
        $this->auto_orientate = isset($config['auto_orientate']) ? $config['auto_orientate'] : $default_config['auto_orientate'];
    }
    
    /**
     * 执行上传操作
     * 若成功，则返回上传文件的各种属性信息
     * 若失败，则设置错误信息并返回false
     * @param bool|string $field
     * @return array|bool
     */
    public function run($field = false){
        if($field === false){
            $file = array_shift($_FILES);
            if($file === null){
                $this->setErrorMsg('没有文件被上传');
                //此处有个BUG，
                //如果上传文件大于php.ini里post_max_size（即便此文件小于upload_max_filesize），
                //则也会出现$_FILES数组为空的现象，此时也会提示为没有文件被上传
                return false;
            }
        }else{
            if(isset($_FILES[$field])){
                $file = $_FILES[$field];
            }else{
                $this->setErrorMsg('没有文件被上传');
                return false;
            }
        }

        if(!is_uploaded_file($file['tmp_name'])){
            $error = (!isset($file['error'])) ? 4 : $file['error'];

            switch($error){
                case UPLOAD_ERR_INI_SIZE:
                    $this->setErrorMsg('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值');
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->setErrorMsg('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->setErrorMsg('文件只有部分被上传');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->setErrorMsg('没有文件被上传');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->setErrorMsg('找不到临时文件夹');
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->setErrorMsg('文件写入失败');
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->setErrorMsg('upload_stopped_by_extension');
                    break;
                default :   $this->setErrorMsg('没有文件被上传');
                    break;
            }
            return false;
        }
        
        if(!$this->isAllowedSize($file['size'])){
            $this->setErrorMsg('文件过大');
            return false;
        }
        
        $this->file_temp = $file['tmp_name'];
        $this->file_size = $file['size'];
        //客户端文件名
        $this->client_name = $file['name'];
        $this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        
        $this->file_ext = LocalFileHelper::getFileExt($file['name']);
        //随机一个唯一文件名
        $this->file_name = FileService::getFileName($this->upload_path, $this->file_ext);
        
        if(!$this->isAllowedType()){
            $this->setErrorMsg('非法文件类型');
            return false;
        }
        
        //没开启URL重写的话，"./uploads/"这样的路径就不是public下了
        if(defined('NO_REWRITE')){
            $destination = './public/'.$this->upload_path.$this->file_name;
        }else{
            $destination = $this->upload_path.$this->file_name;
        }
        if(move_uploaded_file($file['tmp_name'], $destination)){
            $data = array(
                'file_name'=>$this->file_name,
                'raw_name'=>substr($this->file_name, 0, 0 - strlen($this->file_ext)),
                'file_ext'=>$this->file_ext,
                'file_type'=>trim($file['type'], '"'),
                'file_size'=>$file['size'],
                'file_path'=>$this->upload_path,
                'full_path'=>$this->upload_path . $this->file_name,
                'client_name'=>$file['name'],
            );
            $data = array_merge($data, $this->setImgProperties($destination));
            return $data;
        }else{
            $this->setErrorMsg('未知的错误类型');
            return false;
        }
    }
    
    /**
     * 获取错误信息
     */
    public function getErrorMsg(){
        return $this->error_msg;
    }
    
    /**
     * 设置允许的文件类型
     * 若为*，则允许所有类型的文件
     * $types参数为允许的文件类型数组，一般为文件扩展名
     * 自动读取config文件夹中的mimes.php文件，转换为标准mimetype类型
     * @param mixed $types
     */
    private function setAllowedTypes($types){
        if(is_array($types) || $types === '*'){
            $this->allowed_types = $types;
        }else{
            $types = explode('|', $types);
            if(is_array($types)){
                $this->allowed_types = $types;
            }else{
                $this->allowed_types = array();
            }
        }
    }
    
    /**
     * 判断上传的文件是否是允许的文件类型
     * @return bool
     */
    private function isAllowedType(){
        $ext = strtolower(ltrim($this->file_ext, '.'));
        
        //任何情况下不允许直接上传php文件
        if($ext == 'php'){
            return false;
        }
        
        if($this->allowed_types == '*'){
            return true;
        }
        
        if (!in_array($ext, $this->allowed_types)){
            return false;
        }
        
        if(in_array($ext, array('gif', 'jpg', 'jpeg', 'jpe', 'png'), true) && @getimagesize($this->file_temp) === false){
            return false;
        }
        
        //不做mime type类型验证，因为无法确保能真的获取到
        return true;
    }
    
    /**
     * 判断上传的文件大小是否符合设置
     * @param string $size
     * @return bool
     */
    private function isAllowedSize($size){
        if ($this->max_size != 0 && $size > $this->max_size){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 设置错误信息
     * @param string $content
     */
    private function setErrorMsg($content){
        $this->error_msg[] = $content;
    }
    
    /**
     * 获取图片相关属性数组，若不是图片则将is_image设为false，不设置其他属性值
     * @param string $path
     * @return array
     */
    private function setImgProperties($path){
        $x = explode('.', $path);
        if(in_array(strtolower(array_pop($x)), array('jpg', 'png', 'jpeg', 'gif')) && false !== ($metadata = @getimagesize($path))){
            $return = array(
                'is_image'=>true,
                'image_width'=>$metadata[0],
                'image_height'=>$metadata[1],
                'image_mime_type'=>$metadata['mime'],
            );
            //尝试判断图片是否需要旋转
            if($this->auto_orientate && function_exists('exif_read_data') && $metadata['mime'] == 'image/jpeg') {
                $exif = @exif_read_data($path);
                
                if(!empty($exif['Orientation']) && (int)$exif['Orientation'] != 1){
                    //图片需要旋转
                    $image = new ImageService($path, true);
                    $image->save($path);
                    
                    $return['file_size'] = filesize($path);
                }
            }
            
            return $return;
        }else{
            return array(
                'is_image'=>false,
            );
        }
    }
}