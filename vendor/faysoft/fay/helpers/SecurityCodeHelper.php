<?php
namespace fay\helpers;

/**
 * 使用验证码类的方法：
 * $an = new Authnum(验证码长度,图片宽度,图片高度);
 * 实例化时不带参数则默认是四位的60*25尺寸的常规验证码图片
 * 表单页面检测验证码的方法，对比 $_SESSION[an] 是否等于 $_POST[验证码文本框ID]
 * 可选配置：
 * 1.验证码类型：$an->ext_num_type=1; 值为1是小写类型，2是大写类型，3是数字类型
 * 2.干扰点：$an->ext_pixel = false; 值为false表示不添加干扰点
 * 3.干扰线：$an->ext_line = false; 值为false表示不添加干扰线
 * 4.Y轴随机：$an->ext_rand_y = false; 值为false表示不支持图片Y轴随机
 * 5.图片背景：改变 $red $green $blue 三个成员变量的值即可
 */
class SecurityCodeHelper{
	// 图片对象、宽度、高度、验证码长度
	private $im;
	private $im_width;
	private $im_height;
	private $len;
	// 随机字符串、y轴坐标值、随机颜色
	public $randnum;
	private $y;
	private $randcolor;
	// 背景色的红绿蓝，默认是浅灰色
	public $red = 238;
	public $green = 238;
	public $blue = 238;
	/**
	 * 可选设置：验证码类型、干扰点、干扰线、Y轴随机
	 * 设为 false 表示不启用
	 * *
	 */
	// 默认是大小写数字混合型，1 2 3 分别表示 小写、大写、数字型
	public $ext_num_type = '';
	public $ext_pixel = true; // 干扰点
	public $ext_line = true; // 干扰线
	public $ext_rand_y = true; // Y轴随机
	function __construct($len = 4, $im_width = '', $im_height = 25) {
		// 验证码长度、图片宽度、高度是实例化类时必需的数据
		$this->len = $len;
		if($im_width == ''){
			$im_width = $len * 15;
		}
		$this->im_width = $im_width;
		$this->im_height = $im_height;
		$this->im = imagecreate ( $im_width, $im_height );
	}
	// 设置图片背景颜色，默认是浅灰色背景
	function set_bgcolor() {
		imagecolorallocate ( $this->im, $this->red, $this->green, $this->blue );
	}
	// 获得任意位数的随机码
	function get_randnum() {
		$an1 = 'abcdefghijkmnpqrstuvwxyz';
		$an2 = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		$an3 = '23456789';
		if ($this->ext_num_type == '')
			$str = $an1 . $an2 . $an3;
		if ($this->ext_num_type == 1)
			$str = $an1;
		if ($this->ext_num_type == 2)
			$str = $an2;
		if ($this->ext_num_type == 3)
			$str = $an3;
		$randnum = '';
		for($i = 0; $i < $this->len; $i ++) {
			$start = rand ( 1, strlen ( $str ) - 1 );
			$randnum .= substr ( $str, $start, 1 );
		}
		$this->randnum = $randnum;
	}
	// 获得验证码图片Y轴
	function get_y() {
		if ($this->ext_rand_y)
			$this->y = rand ( 5, $this->im_height / 5 );
		else
			$this->y = $this->im_height / 4;
	}
	// 获得随机色
	function get_randcolor() {
		$this->randcolor = imagecolorallocate ( $this->im, rand ( 0, 100 ), rand ( 0, 150 ), rand ( 0, 200 ) );
	}
	// 添加干扰点
	function set_ext_pixel() {
		if ($this->ext_pixel) {
			for($i = 0; $i < 100; $i ++) {
				$this->get_randcolor ();
				imagesetpixel ( $this->im, rand () % 100, rand () % 100, $this->randcolor );
			}
		}
	}
	// 添加干扰线
	function set_ext_line() {
		if ($this->ext_line) {
			for($j = 0; $j < 2; $j ++) {
				$rand_x = rand ( 2, $this->im_width );
				$rand_y = rand ( 2, $this->im_height );
				$rand_x2 = rand ( 2, $this->im_width );
				$rand_y2 = rand ( 2, $this->im_height );
				$this->get_randcolor ();
				imageline ( $this->im, $rand_x, $rand_y, $rand_x2, $rand_y2, $this->randcolor );
			}
		}
	}
	/**
	 * 创建验证码图像：
	 * 建立画布（__construct函数）
	 * 设置画布背景（$this->set_bgcolor();）
	 * 获取随机字符串（$this->get_randnum ();）
	 * 文字写到图片上（imagestring函数）
	 * 添加干扰点/线（$this->set_ext_line(); $this->set_ext_pixel();）
	 * 输出图片
	 */
	function create() {
		$this->set_bgcolor ();
		$this->get_randnum ();
		for($i = 0; $i < $this->len; $i ++) {
			$font = rand ( 4, 6 );
			$x = $i / $this->len * $this->im_width + rand ( 1, $this->len );
			$this->get_y ();
			$this->get_randcolor ();
			imagestring ( $this->im, $font, $x, $this->y, substr ( $this->randnum, $i, 1 ), $this->randcolor );
		}
		$this->set_ext_line ();
		$this->set_ext_pixel ();
		header ( "content-type:image/png" );
		imagepng ( $this->im );
		imagedestroy ( $this->im ); // 释放图像资源
	}
}
// end class
