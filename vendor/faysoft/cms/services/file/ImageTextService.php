<?php
namespace cms\services\file;
use fay\helpers\NumberHelper;

/**
 * 对ImageService的一个扩展，用于往图片上写文字
 */
class ImageTextService extends ImageService{
    /**
     * 在图片上写文本
     * @param string $text 文本
     * @param string $font_file 字体文件路径
     * @param int $size 字体大小
     * @param array|string $color 颜色
     * @param array $margin 定位
     * @param array $text_align 文本对齐方式
     *  - 第一个值是水平位置，取值（left, center, right）
     *  - 第二个值是垂直位置，取值（top, center, bottom）
     * @param float $line_height 行高，例如：1.5代表1.5倍行高
     * @param int $lines 最大显示行数。为0则不限制行数
     * @param int $max_width 文本最大宽度（默认为0）
     *  - 若为0，则根据图片总宽度减left减right作为最大宽度
     *  - 若指定最大宽度，则在$max_width与left, right计算所得宽度中，取较小的一个作为最大宽度
     * @return $this ;
     * @throws FileErrorException
     */
    public function write(
        $text,
        $font_file,
        $size,
        $color,
        $text_align = array('left', 'left'),
        $margin = array(
            'top'=>0,
            'right'=>0,
            'bottom'=>0,
            'left'=>0,
        ),
        $line_height = 1.5,
        $lines = 0,
        $max_width = 0
    ){
        //颜色
        $color = $this->color($color);

        //由于imagettftext是基于文本基线定位，所以其实是无法精确定位的，偏移，防止文本超出图片
        $y_offset = $size * 0.3;

        //格式化定位信息
        foreach(array('top', 'right', 'bottom', 'left') as $key){
            if(isset($margin[$key]) && !NumberHelper::isInt($margin[$key])){
                throw new FileErrorException("\$margin['{$key}']值[{$margin[$key]}]异常，必须为数字");
            }else if(!isset($margin[$key])){
                $margin[$key] = 0;
            }
        }
        //可书写区域
        $inner_box = array(
            'x'=>$margin['left'],
            'y'=>$margin['top'],
            'width'=>$this->width - $margin['left'] - $margin['right'],
            'height'=>$this->height - $margin['top'] - $margin['bottom'],
        );
        
        //文本最大宽度
        if($max_width){
            $max_width = min($max_width, $inner_box['width']);
        }else{
            $max_width = $inner_box['width'];
        }
        
        //假设一行写文，计算文字总宽高
        $text_size = self::getTextSize($text, $size, $font_file);

        //确定起始y坐标（假定只有一行文本）
        if($text_align[1] === 'center'){
            //垂直居中
            $start_y = $inner_box['y'] + ($inner_box['height'] - $text_size['height']) / 2 - $y_offset;
        }else if($text_align[1] === 'bottom'){
            //从下往上书写
            $start_y = $inner_box['y'] + $inner_box['height'] - $y_offset;
        }else{
            //从上往下书写
            $start_y = $inner_box['y'] + $text_size['height'] - $y_offset;
        }
        
        if($text_size['width'] < $max_width){//一行写完
            //确定起始x坐标
            if($text_align[0] === 'center'){
                //文本居中
                $start_x = $inner_box['x'] + ($inner_box['width'] - $text_size['width']) / 2;
            }else if($text_align[0] === 'right'){
                //文本右对齐
                $start_x = $inner_box['x'] + $inner_box['width'] - $text_size['width'];
            }else{
                //文本左对齐
                $start_x = $inner_box['x'];
            }
            
            imagettftext($this->image, $size, 0, $start_x, $start_y, $color, $font_file, $text);
        }else{//文本太长，需要换行处理
            //真实行高（文本高度+行间距）
            $absolute_line_height = $text_size['height'] * $line_height;

            //可书写文本行数
            if($lines){
                $lines = min($lines, floor($inner_box['height']) / ($absolute_line_height));
            }else{
                $lines = floor($inner_box['height']) / ($absolute_line_height);
            }

            //将文本拆成多行
            $text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
            
            //根据书写行数，重新计算$start_y
            if($text_align[1] == 'bottom'){
                //从下往上书写，$start_y向上偏移
                $start_y = $start_y - (count($text_arr) - 1) * $absolute_line_height;
            }else if($text_align[1] == 'center'){
                //垂直居中，$start向上便宜
                $start_y = $start_y - (count($text_arr) - 1) * $absolute_line_height / 2;
            }
            foreach($text_arr as $sub_text){
                $sub_text_size = self::getTextSize($sub_text, $size, $font_file);
                //逐行计算$start_x
                if($text_align[0] === 'center'){
                    //文本居中
                    $start_x = $inner_box['x'] + ($inner_box['width'] - $sub_text_size['width']) / 2;
                }else if($text_align[0] === 'right'){
                    //文本右对齐
                    $start_x = $inner_box['x'] + $inner_box['width'] - $sub_text_size['width'];
                }else{
                    //文本左对齐
                    $start_x = $inner_box['x'];
                }

                imagettftext($this->image, $size, 0, $start_x, $start_y, $color, $font_file, $sub_text);
                $start_y += $absolute_line_height;
            }
        }

        return $this;
    }
    
    /**
     * 获取指定文案被画到图片中后的宽高
     * @param $size
     * @param $font_file
     * @param $text
     * @return array
     */
    public static function getTextSize($text, $size, $font_file){
        $box = imagettfbbox($size, 0, $font_file, $text);

        return array(
            'width'=>$box[2] - $box[0],
            'height'=>$box[1] - $box[7],
        );
    }

    /**
     * 将一个字符串拆分，保证每项宽度都不超过$width
     * @param int $width
     * @param float $size
     * @param string $font_file 字体文件
     * @param string $text 文本
     * @param int $lines 最大显示行数。为0则不限制行数
     * @return array
     */
    protected static function splitByWidth($width, $size, $font_file, $text, $lines = 0){
        $return = array();
        $sub_str = '';
        $str_length = mb_strlen($text, 'utf-8');
        for($i = 0; $i < $str_length; $i++){
            $sub_str .= mb_substr($text, $i, 1, 'utf-8');
            $box = imagettfbbox($size, 0, $font_file, $sub_str);
            if($box[2] - $box[0] > $width){
                //若字符串超出指定长度，截掉最后一个字，并放入待返回数组
                $return[] = mb_substr($sub_str, 0, -1, 'utf-8');
                if($lines && count($return) >= $lines){
                    //已达到指定行数，跳出循环
                    $sub_str = '';
                    break;
                }

                $sub_str = mb_substr($text, $i, 1, 'utf-8');
            }
        }

        if($sub_str){
            $return[] = $sub_str;
        }

        return $return;
    }
}