<?php
namespace cms\services\file;

use cms\models\tables\FilesTable;
use cms\services\OptionService;
use fay\core\ErrorException;
use fay\helpers\NumberHelper;

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
     * @var bool 强制透明，若为false，则根据原图类型判断，若原图为png，则生成图片会自动透明处理
     */
    protected $transparency = false;

    /**
     * 初始化图片信息
     *  - 若是数字，视为本地files表文件id
     *  - 若是数组，视为本地files表行记录（可以少搜一次数据库）
     *  - 若非数字，视为文件路径（可以是本地路径，也可以是url）
     * @param mixed $image
     * @param bool $auto_orientate 是否自动调整图片角度
     * @throws FileErrorException
     */
    public function __construct($image = null, $auto_orientate = true){
        if($image){
            if(NumberHelper::isInt($image)){
                $file = FilesTable::model()->find($image);
                if(!$file){
                    throw new FileErrorException('指定文件ID不存在');
                }
                
                $this->loadLocalFile($file);
            }else if(is_array($image)){
                $this->loadLocalFile($image);
            }else if(is_string($image)){
                $this->loadRemoteFile($image);
            }else{
                throw new FileErrorException('$image参数类型异常['.json_encode($image).']');
            }
            
            if($auto_orientate){
                $this->autoOrientate();
            }
        }
        
        //初始化图片质量
        $this->quality = OptionService::get('system:image_quality', $this->quality);
    }
    
    /**
     * 从base64字符串中创建图像
     * @param $string
     * @return $this
     */
    public function loadFromBase64String($string){
        return $this->loadFromString(base64_decode($string));
    }

    /**
     * 从字符串中的图像流创建图像
     * @param $string
     * @return $this
     * @throws FileErrorException
     */
    public function loadFromString($string){
        $this->image = imagecreatefromstring($string);
        $this->updateSize();
        if(function_exists('finfo_buffer') && !$this->getMimeType()){
            $finfo = finfo_open();
            $this->metadata['mime'] = finfo_buffer($finfo, $string, FILEINFO_MIME_TYPE);
            finfo_close($finfo);
        }
        return $this;
    }

    /**
     * 根据指定宽高，自己生一个
     * @param int $width
     * @param int $height
     * @param bool $transparency 是否透明，默认为false
     * @return $this
     */
    public function loadFromSize($width, $height, $transparency = false){
        $this->image = $this->createCanvas($width, $height, $transparency);
        if($transparency){
            //若创建时设置了透明度，则将$this->transparency设为true，因为这样创建的图片没有mime，无法通过mime自动判断是否需要透明
            $this->transparency = true;
        }
        
        $this->updateSize();
        
        return $this;
    }

    /**
     * 根据指定颜色填充底色
     * @param string $color
     * @return $this
     */
    public function fill($color = '#ffffff'){
        imagefill($this->image, 0, 0, $this->color($color));
        return $this;
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
        
        if($this->width == $width && $this->height == $height){
            //若图片大小没有变化，就不折腾了，直接返回
            return $this;
        }

        $dst_img = $this->createCanvas($width, $height, null);

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
     * 图片翻转
     * @param string $type 取值: horizontal, vertical, both
     * @return $this
     * @throws FileErrorException
     */
    public function flip($type){
        switch($type){
            case 'horizontal':
                if(function_exists('imageflip')){
                    imageflip($this->image, IMG_FLIP_HORIZONTAL);
                }else{
                    $this->flipHorizontal();
                }
                break;
            case 'vertical':
                if(function_exists('imageflip')){
                    imageflip($this->image, IMG_FLIP_VERTICAL);
                }else{
                    $this->flipVertical();
                }
                break;
            case 'both':
                if(function_exists('imageflip')){
                    imageflip($this->image, IMG_FLIP_BOTH);
                }else{
                    $this->flipBoth();
                }
                break;
            default:
                throw new FileErrorException(__CLASS__ . '::' . __METHOD__ . "()\$type参数取值异常[{$type}]");
        }
        
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
        
        //后期处理强制透明
        $this->transparency = true;

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
        
        $dst_img = $this->createCanvas($dst_img_width, $dst_img_height, null);
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
        
        $dst_img = $this->createCanvas($width, $height, null);

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
        
        $dst_img = $this->createCanvas($width, $this->height, null);
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
        
        $dst_img = $this->createCanvas($this->width, $height, null);
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
        $dst_img = $this->createCanvas($this->width + 2 * $width, $this->height + 2 * $width, null);

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
     * 将图片输出为一个圆
     * @param int $diameter 直径
     * @return $this
     */
    public function circle($diameter){
        //后面的图片操作强制透明
        $this->transparency = true;
        
        $diameter *= 2;//先放大2倍处理，直接画圆的话锯齿很严重
        $this->resize($diameter, $diameter);
        
        $dst_img = $this->createCanvas($diameter, $diameter, true);
        $radius = ceil($diameter / 2);
        
        for($i = 0; $i <= $radius; $i++){
            //根据勾股定理，计算需要复制的图片长度
            if($i){
                $width = round(sqrt($radius * $radius - ($radius - $i) * ($radius - $i)));
            }else{
                $width = $diameter * 0.04;
            }
            imagecopy($dst_img, $this->image, $radius - $width, $i, $radius - $width, $i, $width * 2, 1);
            imagecopy($dst_img, $this->image, $radius - $width, $diameter - $i - 1, $radius - $width, $diameter - $i, $width * 2, 1);
        }
        
        $this->image = $dst_img;
        
        $diameter /= 2;
        $this->resize($diameter, $diameter);
        
        return $this;
    }

    /**
     * 往图片上粘贴另一张图片（比如加水印）
     * @param mixed $file
     * @param mixed $margin
     * @param array $align 对齐方式
     *  - 第一个值是水平位置，取值（left, center, right）
     *  - 第二个值是垂直位置，取值（top, center, bottom）
     * @param int $opacity 透明度，取值为0-100，数值越小，透明度越高，0则完全透明，100则不透明（当前后两张图都是透明图时，不可以设置透明度）
     * @return $this
     */
    public function merge($file, $margin = '', $align = array('center', 'center'), $opacity = 100){
        if(is_resource($file) && get_resource_type($file) == 'gd'){
            //直接传入gd资源
            $img_width = imagesx($file);
            $img_height = imagesy($file);
            $img_resource = $file;
        }else if($file instanceof ImageService){
            $img_width = $file->getWidth();
            $img_height = $file->getHeight();
            $img_resource = $file->getImage();
        }else{
            //通过ImageService初始化图片信息
            $img = new ImageService($file);
            $img_width = $img->getWidth();
            $img_height = $img->getHeight();
            $img_resource = $img->getImage();
        }
        
        //格式化定位信息
        $margin = $this->formatMargin($margin);

        //可书写区域
        $inner_box = array(
            'x'=>$margin['left'],
            'y'=>$margin['top'],
            'width'=>$this->width - $margin['left'] - $margin['right'],
            'height'=>$this->height - $margin['top'] - $margin['bottom'],
        );

        //确定起始x坐标
        if($align[0] === 'center'){
            //居中
            $start_x = $inner_box['x'] + ($inner_box['width'] - $img_width) / 2;
        }else if($align[0] === 'right'){
            //右对齐
            $start_x = $inner_box['x'] + $inner_box['width'] - $img_width;
        }else{
            //左对齐
            $start_x = $inner_box['x'];
        }

        //确定起始y坐标
        if($align[1] === 'center'){
            //垂直居中
            $start_y = $inner_box['y'] + ($inner_box['height'] - $img_height) / 2;
        }else if($align[1] === 'bottom'){
            //从下往上
            $start_y = $inner_box['y'] + $inner_box['height'] - $img_height;
        }else{
            //从上往下
            $start_y = $inner_box['y'];
        }

        if($opacity == 100){
            //不需要透明度的话，直接用imagecopy就好了，不需要那么麻烦
            //而且当水印图和底图都透明的时候，透明度还是有bug的
            imagecopy($this->image, $img_resource, $start_x, $start_y, 0, 0, $img_width, $img_height);
        }else{
            //默认情况下，imagecopymerge并不支持水印图自带的透明度，若水印图透明，则会变成黑色背景。
            //而imagecopy支持水印图自身透明度，但是不支持叠加透明度，所以要迂回的实现
            $cut = imagecreatetruecolor($img_width, $img_height);
            imagecopy($cut, $this->image, 0, 0, $start_x, $start_y, $img_width, $img_height);
    
            imagecopy($cut, $img_resource, 0, 0, 0, 0, $img_width, $img_height);
            imagecopymerge($this->image, $cut, $start_x, $start_y, 0, 0, $img_width, $img_height, $opacity);
        }
        
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
        if(function_exists('exif_read_data') && $metadata['mime'] == 'image/jpeg'){
            $metadata['exif'] = @exif_read_data($filename);
        }
        return $metadata;
    }

    /**
     * 获取图片宽度
     * @return int
     */
    public function getWidth(){
        return $this->width;
    }

    /**
     * 获取图片高度
     * @return int
     */
    public function getHeight(){
        return $this->height;
    }

    /**
     * 获取图片资源
     * @return resource
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * 获取图片Mime Type
     * @return string
     */
    public function getMimeType(){
        return isset($this->metadata['mime']) ? $this->metadata['mime'] : '';
    }

    /**
     * 手工设置是否强制透明
     * @param $transparency
     * @return $this
     */
    public function setTransparency($transparency){
        $this->transparency = $transparency;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function getTransparency(){
        return $this->transparency;
    }

    /**
     * 水平翻转图片
     * @return $this
     */
    protected function flipHorizontal(){
        $dst_img = $this->createCanvas($this->width, $this->height, null);

        imagecopyresampled(
            $dst_img, $this->image,
            0, 0, ($this->width - 1), 0,
            $this->width, $this->height, 0 - $this->width, $this->height
        );

        $this->image = $dst_img;

        return $this;
    }

    /**
     * 垂直翻转图片
     * @return $this
     */
    protected function flipVertical(){
        $dst_img = $this->createCanvas($this->width, $this->height, null);

        imagecopyresampled(
            $dst_img, $this->image,
            0, 0, 0, ($this->height - 1),
            $this->width, $this->height, $this->width, 0 - $this->height
        );

        $this->image = $dst_img;

        return $this;
    }

    /**
     * 对角反转（水平+垂直）
     * @return $this
     */
    protected function flipBoth(){
        $dst_img = $this->createCanvas($this->width, $this->height, null);

        imagecopyresampled(
            $dst_img, $this->image,
            0, 0, ($this->width - 1), ($this->height - 1),
            $this->width, $this->height, 0 - $this->width, 0 - $this->height
        );

        $this->image = $dst_img;

        return $this;
    }

    /**
     * 格式化颜色参数，支持：
     *  - 数组，3-4项索引数组，或指定rgb键值的关联数组
     *  - 6位RGB色字符串，例如：#FF0000
     *  - 逗号分割3-4项字符串，例如：255, 0, 0, 0.5
     * @param mixed $color
     * @return array
     * @throws FileErrorException
     */
    protected function formatColor($color){
        if(is_array($color)){
            $allowed_keys = array(
                array('red', 'green', 'blue', 'alpha'),
                array('r', 'g', 'b', 'a'),
                array(0, 1, 2, 3),
            );

            foreach($allowed_keys as $keys){
                if(!isset($color[$keys[0]], $color[$keys[1]], $color[$keys[2]])){
                    continue;
                }

                $color = array(
                    'r'=>$color[$keys[0]],
                    'g'=>$color[$keys[1]],
                    'b'=>$color[$keys[2]],
                    'a'=>isset($color[$keys[3]]) ? $color[$keys[3]] : 0,
                );

                return $color;
            }

            throw new FileErrorException('无法识别的颜色参数: ' . json_encode($color));
        }
        
        if(!is_string($color)){
            throw new FileErrorException('无法识别的颜色参数: ' . json_encode($color));
        }

        if(substr($color, 0, 1) === '#' && strlen($color) === 7){
            return array(
                'r'=>hexdec(substr($color, 1, 2)),
                'g'=>hexdec(substr($color, 3, 2)),
                'b'=>hexdec(substr($color, 5, 2)),
            );
        }
        
        if(strpos($color, ',') != false){
            $exploded_color = explode(',', str_replace(' ', '', $color));
            if(in_array(count($exploded_color), array(3, 4))){
                return array(
                    'r'=>$exploded_color[0],
                    'g'=>$exploded_color[1],
                    'b'=>$exploded_color[2],
                    'a'=>isset($exploded_color[3]) ? $exploded_color[3] : 0,
                );
            }
        }

        throw new FileErrorException('无法识别的颜色参数: ' . json_encode($color));
    }

    /**
     * 格式化margin参数，支持
     *  - css规则，1-4项margin值，可以是数组，也可以是逗号分割的字符串，未指定项根据css规则确定数值
     *  - 索引数组，直接指明top, right, bottom, left值，未指定项默认为0
     * @param mixed $margin
     * @return array
     * @throws FileErrorException
     */
    protected function formatMargin($margin){
        if(is_string($margin)){
            $margin = explode(',', str_replace(' ', '', $margin));
        }
        
        if(!is_array($margin)){
            throw new FileErrorException('无法识别的Margin参数: ' . json_encode($margin));
        }
        
        if(isset($margin[0])){
            //视为索引数组处理
            isset($margin[1]) || $margin[1] = $margin[0];
            isset($margin[2]) || $margin[2] = $margin[0];
            isset($margin[3]) || $margin[3] = $margin[1];
            
            return array(
                'top'=>intval($margin[0]),
                'right'=>intval($margin[1]),
                'bottom'=>intval($margin[2]),
                'left'=>intval($margin[3]),
            );
        }else{
            //视为关联数组处理
            return array(
                'top'=>isset($margin['top']) ? intval($margin['top']) : 0,
                'right'=>isset($margin['right']) ? intval($margin['right']) : 0,
                'bottom'=>isset($margin['bottom']) ? intval($margin['bottom']) : 0,
                'left'=>isset($margin['left']) ? intval($margin['left']) : 0,
            );
        }
    }

    /**
     * 返回一个色值
     * @param mixed $color
     * @return int
     */
    protected function color($color){
        $color = $this->formatColor($color);
        
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
        $mime_type || $mime_type = $this->getMimeType();
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
        switch ($this->getMimeType()){
            case 'image/gif':
                $this->image = \imagecreatefromgif($file_path);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $this->image = \imagecreatefromjpeg($file_path);
                break;
            case 'image/png':
                $this->image = \imagecreatefrompng($file_path);
                break;
            default:
                throw new ErrorException('未知图片类型: ' . json_encode($this->metadata));
                break;
        }
    }

    /**
     * 调整图片方向
     * @return $this
     */
    public function autoOrientate(){
        if(empty($this->metadata['exif']['Orientation'])){
            return $this;
        }
        switch((int)$this->metadata['exif']['Orientation']){
            case 2:
                return $this->flip('horizontal');
            case 3:
                return $this->flip('both');
            case 4:
                return $this->flip('vertical');
            case 5:
                $this->flip('horizontal');
                return $this->rotate(-90);
            case 6:
                return $this->rotate(-90);
            case 7:
                $this->flip('horizontal');
                return $this->rotate(90);
            case 8:
                return $this->rotate(90);
            default:
                return $this;
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
     * @param bool|null $transparency 是否透明处理，若为null，则会根据当前环境猜测是否需要透明
     * @return resource
     */
    protected function createCanvas($width, $height, $transparency = true){
        if($transparency === null){
            if($this->transparency == true || $this->getMimeType() == 'image/png'){
                $transparency = true;
            }else{
                $transparency = false;
            }
        }
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
    
    public function __destruct(){
        $this->destroy();
    }

    /**
     * 销毁对象
     * @return $this
     */
    public function destroy(){
        if(!empty($this->image)
            && is_resource($this->image)
            && get_resource_type($this->image) == 'gd'
        ){
            imagedestroy($this->image);
        }
        
        $this->metadata = array();
        $this->file_path = $this->width = $this->height = null;
        $this->transparency = false;
        
        return $this;
    }

}