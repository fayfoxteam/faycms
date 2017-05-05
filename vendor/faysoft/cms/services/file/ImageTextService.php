<?php
namespace cms\services\file;

/**
 * 对ImageService的一个扩展，用于往图片上写文字
 */
class ImageTextService extends ImageService{
    /**
     * 获取指定文案被画到图片中后的宽高
     * @param $size
     * @param $font_file
     * @param $text
     * @return array
     */
    public static function getTextSize($size, $font_file, $text){
        $box = imagettfbbox($size, 0, $font_file, $text);

        return array(
            'width'=>$box[2] - $box[0],
            'height'=>$box[1] - $box[7],
        );
    }

    /**
     * 往图片上写一行字，居中对齐
     * @param float $size 字体大小
     * @param int $y 文字顶端距离图片顶部距离
     * @param array $color RGBA色数组，如array('r'=>100, 'g'=>100, 'b'=>100, 'a'=>100)
     * @param string $font_file 字体文件
     * @param string $text 文本
     * @param float $line_height 行高，例如：1.5代表1.5倍行高
     * @param int $lines 最大显示行数。为0则不限制行数
     * @param int $max_width 最大宽度，达到这个宽度后换行。为0则超过图片宽度时换行
     * @return $this
     */
    public function textCenter($size, $y, $color, $font_file, $text, $line_height = 1.5, $lines = 0, $max_width = 0){
        //最大宽度
        $max_width || $max_width = $this->width;

        //处理文字颜色
        $font_color = $this->color($color);

        //文字总宽高
        $text_size = self::getTextSize($size, $font_file, $text);

        //第一行的y坐标点
        $start_y = $y + $text_size['height'];

        if($text_size['width'] < $max_width){
            //文字宽度小于绘图区域宽度，直接一行写完就好了
            //第一行的x坐标点
            $start_x = intval(($this->width - $text_size['width']) / 2);

            imagettftext($this->image, $size, 0, $start_x, $start_y, $font_color, $font_file, $text);
        }else{
            //文字宽度大于绘图区域宽度，进行拆分
            $text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
            foreach($text_arr as $sub_text){
                $sub_text_size = self::getTextSize($size, $font_file, $sub_text);
                //x坐标点
                $start_x = intval(($this->width - $sub_text_size['width']) / 2);

                imagettftext($this->image, $size, 0, $start_x, $start_y, $font_color, $font_file, $sub_text);
                $start_y += $text_size['height'] * $line_height;
            }
        }

        return $this;
    }

    /**
     * 往图片上写一行字，左对齐
     * @param float $size 字体大小
     * @param int $x 文字起始X坐标点
     * @param int $y 文字左上角距离图片顶部距离
     * @param array $color RGBA色数组，如array('r'=>100, 'g'=>100, 'b'=>100, 'a'=>100)
     * @param string $font_file 字体文件
     * @param string $text 文本
     * @param float $line_height 行高，例如：1.5代表1.5倍行高
     * @param int $lines 最大显示行数。为0则不限制行数
     * @param int $max_width 最大宽度，达到这个宽度后换行。为0则超过图片宽度时换行
     * @return $this
     */
    public function textLeft($size, $x, $y, $color, $font_file, $text, $line_height = 1.5, $lines = 0, $max_width = 0){
        //最大宽度
        $max_width || $max_width = $this->width - $x;

        //处理文字颜色
        $font_color = $this->color($color);

        //文字总宽高
        $text_size = self::getTextSize($size, $font_file, $text);

        //第一行的y坐标点
        $start_y = $y + $text_size['height'];

        if($text_size['width'] < $max_width){
            //文字宽度小于绘图区域宽度，直接一行写完就好了
            imagettftext($this->image, $size, 0, $x, $start_y, $font_color, $font_file, $text);
        }else{
            //文字宽度大于绘图区域宽度，进行拆分
            $text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
            foreach($text_arr as $sub_text){
                imagettftext($this->image, $size, 0, $x, $start_y, $font_color, $font_file, $sub_text);
                $start_y += $text_size['height'] * $line_height;
            }
        }

        return $this;
    }

    /**
     * 往图片上写一行字，右对齐
     * @param float $size 字体大小
     * @param int $margin 文字右上角距离图片边缘距离
     *  - 若是正数，则是文字右上角距离图片左边距离
     *  - 若是负数，则是文字右上角距离图片右边编剧
     *  - 若是0，则文字紧贴图片右侧
     * @param int $y 文字右上角距离图片顶部距离
     * @param array $color RGBA色数组，如array('r'=>100, 'g'=>100, 'b'=>100, 'a'=>100)
     * @param string $font_file 字体文件
     * @param string $text 文本
     * @param float $line_height 行高，例如：1.5代表1.5倍行高
     * @param int $lines 最大显示行数。为0则不限制行数
     * @param int $max_width 最大宽度，达到这个宽度后换行。为0则超过图片宽度时换行
     * @return $this
     */
    public function textRight($size, $margin, $y, $color, $font_file, $text, $line_height = 1.5, $lines = 0, $max_width = 0){
        //最大宽度
        $max_width || $max_width = $margin > 0 ? $margin : $this->width - $margin;

        //处理文字颜色
        $font_color = $this->color($color);

        //文字总宽高
        $text_size = self::getTextSize($size, $font_file, $text);

        //第一行的y坐标点
        $start_y = $y + $text_size['height'];

        if($text_size['width'] < $max_width){
            //文字宽度小于绘图区域宽度，直接一行写完就好了
            imagettftext($this->image, $size, 0, $this->width - $margin - $text_size['width'], $start_y, $font_color, $font_file, $text);
        }else{
            //文字宽度大于绘图区域宽度，进行拆分
            $text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
            foreach($text_arr as $sub_text){
                $sub_text_size = self::getTextSize($size, $font_file, $sub_text);
                imagettftext($this->image, $size, 0, $this->width - $margin - $sub_text_size['width'], $start_y, $font_color, $font_file, $sub_text);
                $start_y += $text_size['height'] * $line_height;
            }
        }

        return $this;
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