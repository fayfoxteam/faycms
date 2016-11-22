<?php
namespace fay\helpers;

use fay\core\ErrorException;

class Image{
	/**
	 * 获取图片资源
	 * @param $filename
	 * @return resource
	 * @throws ErrorException
	 */
	public static function getImage($filename){
		$imageInfo = getimagesize($filename);
		$img_mime = @strtolower($imageInfo['mime']);
		switch ($img_mime) {
			case 'image/gif':
				$im = \imagecreatefromgif($filename);
				break;
			case 'image/jpeg':
			case 'image/jpg':
				$im = \imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				$im = \imagecreatefrompng($filename);
				break;
			default:
				$im = 'unknow';
				break;
		}
		
		if($im == 'unknow') {
			throw new ErrorException('未知图片类型:' . $img_mime);
		}
		return $im;
	}
	
	/**
	 * 水平翻转图片
	 * @param resource $src_img 原图片资源
	 * @return resource 新图片资源
	 */
	public static function flipHorizontal($src_img){
		$dst_img_width = imagesx($src_img);
		$dst_img_height = imagesy($src_img);
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		for($i=0;$i<$dst_img_width;$i++){
			imagecopy($dst_img, $src_img, $dst_img_width-$i, 0, $i, 0, 1, $dst_img_height);
		}
		
		return $dst_img;
	}
	
	/**
	 * 垂直翻转图片
	 * @param resource $src_img 原图片资源
	 * @return resource 新图片资源
	 */
	public static function flipVertical($src_img){
		$dst_img_width = imagesx($src_img);
		$dst_img_height = imagesy($src_img);
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		for($i=0;$i<$dst_img_height;$i++){
			imagecopy($dst_img, $src_img, 0, $dst_img_height-$i, 0, $i, $dst_img_width, 1);
		}
		return $dst_img;
	}
	
	/**
	 * 旋转一定角度，逆时针旋转
	 * @param resource $src_img 原图片资源
	 * @param int $degrees 角度
	 * @return resource 新图片资源
	 */
	public static function rotate($src_img, $degrees){
		$dst_img = imagerotate($src_img, $degrees, 0);
		
		return $dst_img;
	}
	
	/**
	 * 按比例调整大小
	 * @param resource $src_img 原图片资源
	 * @param int $percent 比例
	 * @return resource 新图片资源
	 */
	public static function scalesc($src_img, $percent){
		$dst_img_width = ceil(imagesx($src_img) * $percent);
		$dst_img_height = ceil(imagesy($src_img) * $percent);
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_img_width, $dst_img_height, imagesx($src_img), imagesy($src_img));
		
		return $dst_img;
	}
	
	/**
	 * 根据指定起始点坐标点，宽高对图片进行裁剪
	 * @param resource $src_img
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @return resource
	 */
	public static function crop($src_img, $x, $y, $width, $height){
		$dst_img = imagecreatetruecolor($width, $height);
		imagecopyresampled($dst_img, $src_img, 0, 0, $x, $y, $width, $height, $width, $height);
		return $dst_img;
	}
	
	/**
	 * 水平切割图片，从左到右取$width长度
	 * 
	 * @param resource $src_img
	 * @param int $width
	 * @return resource
	 */
	public static function cutHorizontal($src_img, $width){
		$dst_img_width = $width;
		$dst_img_height = imagesy($src_img);
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		imagecopy($dst_img, $src_img, 0, 0, 0, 0, $dst_img_width, $dst_img_height);
		
		return $dst_img;
	}
	
	/**
	 * 垂直切割图片，从上往下取$height长度
	 * 
	 * @param resource $src_img
	 * @param int $height
	 * @return resource
	 */
	public static function cutVertical($src_img, $height){
		$dst_img_width = imagesx($src_img);
		$dst_img_height = $height;
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		imagecopy($dst_img, $src_img, 0, 0, 0, 0, $dst_img_width, $dst_img_height);
		
		return $dst_img;
	}
	
	/**
	 * 按比例缩放图片，并按规格裁剪
	 * 
	 * @param resource $src_img
	 * @param int $width
	 * @param int $height
	 * @return resource
	 */
	public static function resize($src_img, $width, $height){
		$src_img_width = imagesx($src_img);
		$src_img_height = imagesy($src_img);
		
		//原图与目标尺寸的宽高比
		$width_ratio = $src_img_width / $width;
		$height_ratio = $src_img_height / $height;

		$dst_img = imagecreatetruecolor($width, $height);
		if($width_ratio < $height_ratio){
			//取比例小的作为缩放比
			//图太长
			imagecopyresampled($dst_img, $src_img, 0, 0, ceil(($src_img_width - $width * $width_ratio)/2), ($src_img_height - $height * $width_ratio)/2, $width, $height, $width * $width_ratio, $height * $width_ratio);
		}else{
			//图太宽
			imagecopyresampled($dst_img, $src_img, 0, 0, ($src_img_width - $width * $height_ratio)/2, ceil(($src_img_height - $height * $height_ratio)/2), $width, $height, $width * $height_ratio, $height * $height_ratio);
		}
		return $dst_img;
	}
	
	/**
	 * 用小图填充整个大图背景，用小图片铺满背景
	 * 
	 * @param resource $src_img
	 * @param string $img_file 文件路径
	 * @return resource
	 */
	public static function fillByImage($src_img, $img_file){
		$mat_img = self::getImage($img_file);
		$mat_img_width = imagesx($mat_img);
		$mat_img_height = imagesy($mat_img);
		$src_width = imagesx($src_img);
		$src_height = imagesy($src_img);
		
		for($j=0;$j<$src_height;$j=$j+$mat_img_height){
			for($i=0;$i<$src_width;$i=$i+$mat_img_width){
				imagecopy($src_img, $mat_img, $i, $j, 0, 0, $mat_img_width, $mat_img_height);
			}
		}
	}
	
	/**
	 * 给图片添加一条1像素宽的边框（图片不会变大，最外层1像素会被覆盖）
	 * @param resource $src_img
	 * @param array $color RGB色数组，如array(255, 255, 255)
	 */
	public static function addBorder($src_img, $color){
		$src_width = imagesx($src_img);
		$src_height = imagesy($src_img);
		$lincolor = imagecolorallocate($src_img, $color[0], $color[1], $color[2]);
		
		imageline($src_img, 0, 0, $src_width-1, 0, $lincolor);	//上边
		imageline($src_img, $src_width-1, 0, $src_width-1, $src_height-1, $lincolor);	//右边
		imageline($src_img, 0, $src_height-1, $src_width-1, $src_height-1, $lincolor);	//下边
		imageline($src_img, 0, 0, 0, $src_height-1, $lincolor);	//左边
	}
	
	/**
	 * 往图片上写一行字，居中对齐
	 * @param resource $src_img 图像资源
	 * @param float $size 字体大小
	 * @param int $y 文字顶端距离图片顶部距离
	 * @param int $color 见 imagecolorallocate()。
	 * @param string $font_file 字体文件
	 * @param string $text 文本
	 * @param float $line_height 行高，例如：1.5代表1.5倍行高
	 * @param int $lines 最大显示行数。为0则不限制行数
	 * @param int $max_width 最大宽度，达到这个宽度后换行。为0则超过图片宽度时换行
	 * @return resource
	 */
	public static function textCenter($src_img, $size, $y, $color, $font_file, $text, $line_height = 1.5, $lines = 0, $max_width = 0){
		//图片宽度
		$img_width = imagesx($src_img);
		$max_width || $max_width = $img_width;
		
		$box = imagettfbbox($size, 0, $font_file, $text);
		
		//文字总宽高
		$text_width = $box[2] - $box[0];
		$text_height = $box[1] - $box[7];
		
		//第一行的y坐标点
		$start_y = $y + $text_height;
		
		if($text_width < $max_width){
			//文字宽度小于绘图区域宽度，直接一行写完就好了
			//第一行的x坐标点
			$start_x = intval(($img_width - $text_width) / 2);
			
			imagettftext($src_img, 14, 0, $start_x, $start_y, $color, $font_file, $text);
		}else{
			//文字宽度大于绘图区域宽度，进行拆分
			$text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
			foreach($text_arr as $sub_text){
				$box = imagettfbbox($size, 0, $font_file, $sub_text);
				$text_width = $box[2] - $box[0];
				//x坐标点
				$start_x = intval(($img_width - $text_width) / 2);
				
				imagettftext($src_img, 14, 0, $start_x, $start_y, $color, $font_file, $sub_text);
				$start_y += $text_height * $line_height;
			}
		}
		
		return $src_img;
	}
	
	/**
	 * 往图片上写一行字，居左对齐
	 * @param resource $src_img 图像资源
	 * @param float $size 字体大小
	 * @param int $x 文字起始X坐标点
	 * @param int $y 文字顶端距离图片顶部距离
	 * @param int $color 见 imagecolorallocate()。
	 * @param string $font_file 字体文件
	 * @param string $text 文本
	 * @param float $line_height 行高，例如：1.5代表1.5倍行高
	 * @param int $lines 最大显示行数。为0则不限制行数
	 * @param int $max_width 最大宽度，达到这个宽度后换行。为0则超过图片宽度时换行
	 * @return resource
	 */
	public static function textLeft($src_img, $size, $x, $y, $color, $font_file, $text, $line_height = 1.5, $lines = 0, $max_width = 0){
		//图片宽度
		$img_width = imagesx($src_img);
		$max_width || $max_width = $img_width;
		
		$box = imagettfbbox($size, 0, $font_file, $text);
		
		//文字总宽高
		$text_width = $box[2] - $box[0];
		$text_height = $box[1] - $box[7];
		
		//第一行的y坐标点
		$start_y = $y + $text_height;
		
		if($text_width < $max_width){
			//文字宽度小于绘图区域宽度，直接一行写完就好了
			imagettftext($src_img, 14, 0, $x, $start_y, $color, $font_file, $text);
		}else{
			//文字宽度大于绘图区域宽度，进行拆分
			$text_arr = self::splitByWidth($max_width, $size, $font_file, $text, $lines);
			foreach($text_arr as $sub_text){
				imagettftext($src_img, 14, 0, $x, $start_y, $color, $font_file, $sub_text);
				$start_y += $text_height * $line_height;
			}
		}
		
		return $src_img;
	}
	
	/**
	 * 将一个字符串拆分，保证每项宽度都不超过$width
	 * @param int $width
	 * @param float $size
	 * @param string $font_file 字体文件
	 * @param string $text 文本
	 * @return array
	 */
	private static function splitByWidth($width, $size, $font_file, $text, $lines = 0){
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