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
     * @var int jpg, gif图片质量参数
     */
    protected $quality = 75;

    /**
     * @var int png 图片质量参数
     */
    protected $compression = 9;

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
        
        //初始化图片质量
        $this->quality = OptionService::get('system:image_quality', $this->quality);
    }

    /**
     * 缩放到指定大小，若宽高未指定，则会根据原图比例计算宽高
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resize($width, $height){
        $width_ratio = $this->width / $width;
        $height_ratio = $this->height / $height;

        $dst_img = $this->createCanvas($width, $height, $this->metadata['mime'] == 'image/png');

        if($width_ratio < $height_ratio){
            //取比例小的作为缩放比
            //图太长
            imagecopyresampled(
                $dst_img,
                $this->image,
                0,
                0,
                ceil(($this->width - $width * $width_ratio) / 2),
                ($this->height - $height * $width_ratio) / 2,
                $width,
                $height,
                $width * $width_ratio,
                $height * $width_ratio
            );
        }else{
            //图太宽
            imagecopyresampled(
                $dst_img,
                $this->image,
                0,
                0,
                ($this->width - $width * $height_ratio) / 2,
                ceil(($this->height - $height * $height_ratio) / 2),
                $width,
                $height,
                $width * $height_ratio,
                $height * $height_ratio
            );
        }

        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 水平翻转图片
     * @return $this
     */
    public function flipHorizontal(){
        $dst_img = $this->createCanvas($this->width, $this->height, $this->metadata['mime'] == 'image/png');
        
        for($i = 0; $i < $this->width; $i++){
            imagecopy($dst_img, $this->image, $this->width - $i, 0, $i, 0, 1, $this->height);
        }

        $this->setImage($dst_img);

        return $this;
    }

    /**
     * 垂直翻转图片
     * @return $this
     */
    public function flipVertical(){
        $dst_img = $this->createCanvas($this->width, $this->height, $this->metadata['mime'] == 'image/png');
        
        for($i = 0; $i < $this->height; $i++){
            imagecopy(
                $dst_img,
                $this->image,
                0,
                $this->height - $i,
                0,
                $i,
                $this->width,
                1
            );
        }
        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 旋转一定角度，逆时针旋转。旋转后空白部分为透明色（需要png方式输出）
     * @param int $degrees 角度
     * @return $this
     */
    public function rotate($degrees){
        $this->image = imagerotate(
            $this->image,
            $degrees,
            imagecolorallocatealpha($this->image, 0, 0, 0, 127)
        );

        $this->updateSize();
        
        return $this;
    }

    /**
     * 按比例调整大小
     * @param int $percent 比例（例如0.1就是10%）
     * @return $this
     */
    public function scalesc($percent){
        $dst_img_width = ceil($this->width * $percent);
        $dst_img_height = ceil($this->height * $percent);
        
        $dst_img = $this->createCanvas($dst_img_width, $dst_img_height, $this->metadata['mime'] == 'image/png');
        imagecopyresampled($dst_img, $this->image, 0, 0, 0, 0, $dst_img_width, $dst_img_height, $this->width, $this->height);

        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 根据指定起始点坐标点，宽高对图片进行裁剪
     *  - 若$x, $y坐标点超出图片范围，会抛出异常
     *  - 若$width, $height超出图片范围，则只裁剪到图片边缘
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @return $this
     * @throws FileErrorException
     */
    public function crop($x, $y, $width, $height){
        if($x > $this->width || $y > $this->height){
            throw new FileErrorException("裁剪起始坐标点[{$x}, {$y}]超出图片范围[{$this->width}x{$this->height}]");
        }
        
        if($x + $width > $this->width){
            $width = $this->width - $x;
        }
        if($y + $height > $this->height){
            $height = $this->height - $y;
        }
        
        $dst_img = $this->createCanvas($width, $height, $this->metadata['mime'] == 'image/png');

        imagecopyresampled($dst_img, $this->image, 0, 0, $x, $y, $width, $height, $width, $height);
        
        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 水平切割图片，从左到右取$width长度
     *  - 若$width超出图片范围，则不做裁剪，直接返回
     * @param int $width
     * @return $this
     */
    public function cutHorizontal($width){
        if($width > $this->width){
            //裁剪尺寸超出图片返回，不做裁剪
            return $this;
        }
        
        $dst_img = $this->createCanvas($width, $this->height, $this->metadata['mime'] == 'image/png');
        imagecopy($dst_img, $this->image, 0, 0, 0, 0, $width, $this->height);

        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 垂直切割图片，从上往下取$height长度
     *  - 若$height超出图片范围，则不做裁剪，直接返回
     * @param int $height
     * @return $this
     */
    public function cutVertical($height){
        if($height > $this->height){
            //裁剪尺寸超出图片返回，不做裁剪
            return $this;
        }
        
        $dst_img = $this->createCanvas($this->width, $height, $this->metadata['mime'] == 'image/png');
        imagecopy($dst_img, $this->image, 0, 0, 0, 0, $this->width, $height);

        $this->setImage($dst_img);

        return $this;
    }

    /**
     * 用小图填充整个大图背景，用小图片铺满背景
     *
     * @param string $img_file 文件路径
     * @return $this
     */
    public function fillByImage($img_file){
        $bg_img = new ImageService($img_file);

        for($j = 0; $j < $this->height; $j = $j + $bg_img->getHeight()){
            for($i = 0; $i < $this->width; $i = $i + $bg_img->getWidth()){
                imagecopy($this->image, $bg_img->getImage(), $i, $j, 0, 0, $bg_img->getWidth(), $bg_img->getHeight());
            }
        }
        
        return $this;
    }

    /**
     * 给图片添加一条指定像素宽的边框
     * @param array $color RGBA色数组，如array('r'=>100, 'g'=>100, 'b'=>100, 'a'=>100)
     * @param int $width 边框宽度
     * @return $this
     */
    public function addBorder($color, $width = 1){
        $dst_img = $this->createCanvas($this->width + 2 * $width, $this->height + 2 * $width, $this->metadata['mime'] == 'image/png');

        $line_color = $this->color($color);
        
        for($i = 0; $i < $width; $i++){
            //上边
            imageline($dst_img, 0, $i, $this->width + $width, $i, $line_color);
        }
        for($i = 0; $i < $width; $i++){
            //右边
            imageline($dst_img, $this->width + $width + $i, 0, $this->width + $width + $i, $this->height + $width, $line_color);
        }
        for($i = 0; $i < $width; $i++){
            //下边
            imageline($dst_img, $width, $this->height + $width + $i, $this->width + 2 * $width, $this->height + $width + $i, $line_color);
        }
        for($i = 0; $i < $width; $i++){
            //左边
            imageline($dst_img, $i, $width, $i, $this->height + 2 * $width, $line_color);
        }
        
        //把图放上去
        imagecopy($dst_img, $this->image, $width, $width, 0, 0, $this->width, $this->height);
        
        $this->setImage($dst_img);
        
        return $this;
    }

    /**
     * 输出图片
     * @param string $mime_type 若为空，则默认为原图类型
     */
    public function output($mime_type = ''){
        $this->generate($mime_type);
    }

    /**
     * 保存图片
     * @param string $filename 文件路径
     * @param string $mime_type 若为空，则默认为原图类型
     */
    public function save($filename, $mime_type = ''){
        $this->generate($mime_type, $filename);
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
     * @return int
     */
    public function getWidth(){
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(){
        return $this->height;
    }

    /**
     * @return resource
     */
    public function getImage(){
        return $this->image;
    }
    
    protected function color($color){
        if(!empty($color['a'])){
            return imagecolorallocatealpha($this->image, $color['r'], $color['g'], $color['b'], $color['a']);
        }
        
        return imagecolorallocate($this->image, $color['r'], $color['g'], $color['b']);
    }

    /**
     * 输出或生成图片
     * @param null|string $filename 若为null，则输出，否则保存为文件
     * @param string $mime_type
     */
    protected function generate($mime_type = '', $filename = null){
        $mime_type || $mime_type = $this->metadata['mime'];
        switch ($mime_type) {
            case 'image/gif':
                if(!$filename){
                    header('Content-type: image/gif');
                }
                imagegif($this->image, $filename);
                break;
            case 'image/png':
                imagesavealpha($this->image, true);
                if(!$filename){
                    header('Content-type: image/png');
                }
                imagepng($this->image, $filename);
                break;
            case 'image/jpeg':
            case 'image/jpg':
            default:
                //默认输出jpg
                imageinterlace($this->image, true);
                if(!$filename){
                    header('Content-type: image/jpeg');
                }
                imagejpeg($this->image, $filename, $this->quality);
                break;
        }
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
     * 给$this->image赋值，并自动更新宽高属性
     * @param resource $image
     */
    protected function setImage($image){
        $this->image = $image;
        $this->updateSize();
    }

    /**
     * 重新设置图片宽高属性（缩放/裁剪后自动调用）
     */
    protected function updateSize(){
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }
}