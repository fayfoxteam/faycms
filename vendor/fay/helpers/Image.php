<?php
namespace fay\helpers;

use fay\core\Exception;

class Image{
	/**
	 * 获取图片资源
	 * @return resource
	 */
	public static function getImage($filename){
		$imageInfo = getimagesize($filename);
		$img_mime = strtolower($imageInfo['mime']);
		switch ($img_mime) {
			case 'image/gif':
				$im = imagecreatefromgif($filename);
				break;
			case 'image/jpeg':
			case 'image/jpg':
				$im = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				$im = imagecreatefrompng($filename);
				break;
			default:
				$im = 'unknow';
				break;
		}
		
		if($im == 'unknow') {
			throw new Exception('未知图片类型');
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
	 * @param int $angle 角度
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
		$dst_img_width = imagesx($src_img) * $percent;
		$dst_img_height = imagesy($src_img) * $percent;
		$dst_img = imagecreatetruecolor($dst_img_width, $dst_img_height);
		imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $dst_img_width, $dst_img_height, imagesx($src_img), imagesy($src_img));
		
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
	public static function cut($src_img, $x, $y, $width, $height){
		$dst_img = ImageCreateTrueColor($width, $height);
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
	public static function zoom($src_img, $width, $height){
		$src_img_width = imagesx($src_img);
		$src_img_height = imagesy($src_img);
		
		if($width/$height < $src_img_width/$src_img_height){
			$percent = $height/$src_img_height;
			$src_img = self::scalesc($src_img, $percent);
			$dst_img = imagecreatetruecolor($width, $height);
			imagecopyresized($dst_img, $src_img, 0, 0, (imagesx($src_img)-$width)/2, 0, $width, $height, $width, $height);
		}else{
			$percent = $width/$src_img_width;
			$src_img = self::scalesc($src_img, $percent);
			$dst_img = imagecreatetruecolor($width, $height);
			imagecopyresized($dst_img, $src_img, 0, 0, 0, (imagesy($src_img)-$height)/2, $width, $height, $width, $height);
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
		$mat_img = self::get_img($img_file);
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
}