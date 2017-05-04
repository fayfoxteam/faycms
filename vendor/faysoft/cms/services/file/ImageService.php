<?php
namespace cms\services\file;

use cms\models\tables\FilesTable;
use cms\services\OptionService;
use fay\core\ErrorException;
use fay\helpers\NumberHelper;

//https://github.com/elboletaire/Watimage/blob/master/src/Image.php

class ImageService{
    /**
     * @var resource
     */
    protected $image;

    /**
     * @var string 文件路径
     */
    protected $file_path;

    /**
     * 是否为本地文件系统内的文件（即在files表中，初始化时传入的是数组或files表ID）
     * @var bool
     */
    protected $is_local_file = false;

    /**
     * @var array Meta信息
     */
    protected $metadata = array();

    /**
     * @var int 图片宽度（初始的时候与$this->metadata中的width一致，缩放/裁剪后可能变化）
     */
    protected $width;

    /**
     * @var int 图片高度（初始的时候与$this->metadata中的height一致，缩放/裁剪后可能变化）
     */
    protected $height;

    /**
     * 初始化图片信息
     *  - 若是数字，视为本地files表文件id
     *  - 若是数组，视为本地files表行记录（可以少搜一次数据库）
     *  - 若非数字，视为文件路径（可以是本地路径，也可以是url）
     * @param mixed $image
     * @throws FileErrorException
     */
    public function __construct($image){
        if(NumberHelper::isInt($image)){
            $file = FilesTable::model()->find($image);
            if(!$file){
                throw new FileErrorException('指定文件ID不存在');
            }
            
            $this->loadLocalFile($file);
        }else if(is_array($image)){
            $this->loadLocalFile($image);
        }else{
            $this->loadRemoteFile($image);
        }
    }

    /**
     * 缩放到指定大小
     * @param int $width
     * @param int $height
     */
    public function resize($width, $height){
        //原图与目标尺寸的宽高比
        $width_ratio = $this->width / $width;
        $height_ratio = $this->height / $height;

        $dst_img = $this->createCanvas($width, $height, $this->metadata['mime'] == 'image/png');

        if($width_ratio < $height_ratio){
            //取比例小的作为缩放比
            //图太长
            imagecopyresampled($dst_img, $this->image, 0, 0, ceil(($this->width - $width * $width_ratio)/2), ($this->height - $height * $width_ratio)/2, $width, $height, $width * $width_ratio, $height * $width_ratio);
        }else{
            //图太宽
            imagecopyresampled($dst_img, $this->image, 0, 0, ($this->width - $width * $height_ratio)/2, ceil(($this->height - $height * $height_ratio)/2), $width, $height, $width * $height_ratio, $height * $height_ratio);
        }

        $this->image = $dst_img;
    }


    /**
     * 输出或保存图片
     * @param string $filename 若非空，则保存到指定路径
     * @param string $mime_type 若为空，则默认为原图类型
     */
    public function output($filename = '', $mime_type = ''){
        $mime_type || $mime_type = $this->metadata['mime'];
        switch ($mime_type) {
            case 'image/gif':
                if($filename){
                    imagegif($this->image, $filename);
                }else{
                    header('Content-type: image/gif');
                    imagegif($this->image);
                }
                break;
            case 'image/png':
                if($filename){
                    imagepng($this->image, $filename);
                }else{
                    header('Content-type: image/png');
                    imagepng($this->image);
                }
                break;
            case 'image/jpeg':
            case 'image/jpg':
            default:
                //默认输出jpg
                if($filename){
                    imagejpeg($this->image, $filename);
                }else{
                    header('Content-type: image/jpeg');
                    imagejpeg($this->image, null, \F::input()->get('q', 'intval', OptionService::get('system:image_quality', 75)));
                }
                break;
        }
    }

    /**
     * 获取指定文件的meta信息
     * @param $filename
     * @return array
     */
    public static function getMetadataFromFile($filename){
        $info = getimagesize($filename);
        $metadata = array(
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $info['mime'],
            'exif' => null,
        );
        if (function_exists('exif_read_data') && $metadata['mime'] == 'image/jpeg') {
            $metadata['exif'] = @exif_read_data($filename);
        }
        return $metadata;
    }

    /**
     * 根据files表相关信息，获取对应文件
     * @param $file
     */
    protected function loadLocalFile($file){
        $this->is_local_file = true;
        $this->loadRemoteFile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
    }

    /**
     * 根据文件路径获取图片（可以是本地路径，也可以是url）
     * @param string $file_path
     * @throws ErrorException
     */
    protected function loadRemoteFile($file_path){
        $this->file_path = $file_path;
        
        //设置Meta信息
        $this->setMetadata();

        //获取图片资源
        switch ($this->metadata['mime']){
            case 'image/gif':
                $this->image = \imagecreatefromgif($this->file_path);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $this->image = \imagecreatefromjpeg($this->file_path);
                break;
            case 'image/png':
                $this->image = \imagecreatefrompng($this->file_path);
                break;
            default:
                throw new ErrorException('未知图片类型:' . $this->metadata['mime']);
                break;
        }
    }

    /**
     * 根据图片文件路径，获取图片Meta信息
     */
    protected function setMetadata(){
        $this->metadata = self::getMetadataFromFile($this->file_path);
        
        $this->width = $this->metadata['width'];
        $this->height = $this->metadata['height'];
    }

    /**
     * 创建一块画布
     * @param int $width
     * @param int $height
     * @param bool $transparency 是否透明处理
     * @return resource
     */
    protected function createCanvas($width, $height, $transparency = true){
        $image = imagecreatetruecolor($width, $height);

        if($transparency){
            //透明处理
            $alpha = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $alpha);
            imagesavealpha($image, true);
            imagealphablending($image, true);
        }
        
        return $image;
    }

    /**
     * 重新设置图片宽高属性（缩放/裁剪后自动调用）
     */
    protected function updateSize(){
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }
}