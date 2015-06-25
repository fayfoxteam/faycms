<?php
namespace fay\helpers;

class String{
	/**
	 * 类似PHP的nl2br函数，只是将回车转为p标签包裹
	 */
	public static function nl2p($text){
		return "<p>" . str_replace(PHP_EOL, "</p><p>", $text) . "</p>";
	}
	
	/**
	 * 返回4组6位的哈希值
	 * 一种生成短连接的算法
	 * @param string $input
	 * @return string
	 */
	public static function base62($input){
		$base62 = array (
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
			'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
			'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
			'y', 'z', 'A','B','C','D','E','F','G',
			'H','I','J','K','L','M','N','O','P',
			'Q','R','S','T','U','V','W','X','Y', 'Z',
			'0', '1', '2', '3', '4', '5','6','7','8','9'
		);
		$hex = md5($input);
		$hexLen = strlen($hex);
		$subHexLen = $hexLen / 8;
		$output = array();
		for ($i = 0; $i < $subHexLen; $i++) {
			$subHex = substr ($hex, $i * 8, 8);
			$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
			$out = '';
			for ($j = 0; $j < 6; $j++) {
				$val = 0x0000003D & $int;
				$out .= $base62[$val];
				$int = $int >> 5;
			}
			$output[] = $out;
		}
		return $output;
	}
	
	/**
	 * 随机字符串
	 * 
	 * @param string $type
	 *     alpha: 含有大小写字母。
	 *     alnum: 含有大小写字母以及数字。
	 *     numeric: 数字字符串。
	 *     nozero: 不含零的数字字符串。
	 *     unique: 用 MD5 and uniqid()加密的字符串。注意：第二个长度参数在这种类型无效。均返回一个32位长度的字符串。
	 * @param int $length
	 */
	public static function random($type = 'alnum', $length = 16) {
		switch ($type) {
			case 'basic' :
				return mt_rand();
				break;
			case 'alnum' :
			case 'numeric' :
			case 'nozero' :
			case 'alpha' :
				
				switch ($type) {
					case 'alpha' :
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'alnum' :
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric' :
						$pool = '0123456789';
						break;
					case 'nozero' :
						$pool = '123456789';
						break;
				}
				
				$str = '';
				for($i = 0; $i < $length; $i ++){
					$str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
				}
				return $str;
				break;
			case 'unique' :
			case 'md5' :
				return md5(uniqid(mt_rand()));
				break;
		}
	}
	
	/**
	 * 格式化字符串，超过指定长度部分用...代替
	 * 若传入filters，会先用filters过滤后再格式化
	 * @param string $str
	 * @param int $length
	 * @param bool $encode 若该参数为true，则截取后会对字符串做html实体转换处理
	 * @return string
	 */
	public static function niceShort($str, $length, $encode = false){
		if(mb_strlen($str, 'UTF-8') > $length){
			$str = mb_substr($str, 0, $length, 'UTF-8').'...';
		}
		if($encode){
			$str = Html::encode($str);
		}
		return $str;
	}
	
	/**
	 * 四舍五入返回一个保留2位小数的数字
	 * @param string|float $number
	 * @return string
	 */
	public static function money($number){
		return number_format($number, 2, '.', '');
	}
	
	/**
	 * 下划线分割转大小写分割<br>
	 * 若$ucfirst为false，首字母小写，默认为所有分词首字母大写
	 */
	public static function underscore2case($str, $ucfirst = true){
		$explodes = explode('_', $str);
		foreach($explodes as $key => &$e){
			if(!$key && $ucfirst){
				$e = ucfirst($e);
			}else if($key){
				$e = ucfirst($e);
			}
		}
		return implode('', $explodes);
	}
	
	/**
	 * 连字符（中横线）分割转大小写分割<br>
	 * 若$ucfirst为false，首字母小写，默认为所有分词首字母大写
	 */
	public static function hyphen2case($str, $ucfirst = true){
		$explodes = explode('-', $str);
		foreach($explodes as $key => &$e){
			if(!$key && $ucfirst){
				$e = ucfirst($e);
			}else if($key){
				$e = ucfirst($e);
			}
		}
		return implode('', $explodes);
	}
	
	/**
	 * 斜杠分割转大小写分割<br>
	 * 若$ucfirst为false，首字母小写，默认为所有分词首字母大写
	 */
	public static function slashes2case($str, $ucfirst = true){
		$explodes = explode('/', $str);
		foreach($explodes as $key => &$e){
			if(!$key && $ucfirst){
				$e = ucfirst($e);
			}else if($key){
				$e = ucfirst($e);
			}
		}
		return implode('', $explodes);
	}
	
	public static function removeXSS($val) {
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
		// this prevents some character re-spacing such as <java\0script>
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
		$val = preg_replace ( '/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val );
		
		// straight replacements, the user should never need these since they're normal characters
		// this prevents like <IMG SRC=@avascript:alert('XSS')>
		$search = 'abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';
		for($i = 0; $i < strlen ( $search ); $i ++) {
			// ;? matches the ;, which is optional
			// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
			
			// @ @ search for the hex values
			$val = preg_replace ( '/(&#[xX]0{0,8}' . dechex ( ord ( $search [$i] ) ) . ';?)/i', $search [$i], $val ); // with a ;
			                                                                                           // @ @ 0{0,7} matches '0' zero to seven times
			$val = preg_replace ( '/(&#0{0,8}' . ord ( $search [$i] ) . ';?)/', $search [$i], $val ); // with a ;
		}
		
		// now the only remaining whitespace attacks are \t, \n, and \r
		$ra1 = Array (
				'javascript',
				'vbscript',
				'expression',
				'applet',
				'meta',
				'xml',
				'blink',
				'link',
				'style',
				'script',
				'embed',
				'object',
				'iframe',
				'frame',
				'frameset',
				'ilayer',
				'layer',
				'bgsound',
				'title',
				'base' 
		);
		$ra2 = Array (
				'onabort',
				'onactivate',
				'onafterprint',
				'onafterupdate',
				'onbeforeactivate',
				'onbeforecopy',
				'onbeforecut',
				'onbeforedeactivate',
				'onbeforeeditfocus',
				'onbeforepaste',
				'onbeforeprint',
				'onbeforeunload',
				'onbeforeupdate',
				'onblur',
				'onbounce',
				'oncellchange',
				'onchange',
				'onclick',
				'oncontextmenu',
				'oncontrolselect',
				'oncopy',
				'oncut',
				'ondataavailable',
				'ondatasetchanged',
				'ondatasetcomplete',
				'ondblclick',
				'ondeactivate',
				'ondrag',
				'ondragend',
				'ondragenter',
				'ondragleave',
				'ondragover',
				'ondragstart',
				'ondrop',
				'onerror',
				'onerrorupdate',
				'onfilterchange',
				'onfinish',
				'onfocus',
				'onfocusin',
				'onfocusout',
				'onhelp',
				'onkeydown',
				'onkeypress',
				'onkeyup',
				'onlayoutcomplete',
				'onload',
				'onlosecapture',
				'onmousedown',
				'onmouseenter',
				'onmouseleave',
				'onmousemove',
				'onmouseout',
				'onmouseover',
				'onmouseup',
				'onmousewheel',
				'onmove',
				'onmoveend',
				'onmovestart',
				'onpaste',
				'onpropertychange',
				'onreadystatechange',
				'onreset',
				'onresize',
				'onresizeend',
				'onresizestart',
				'onrowenter',
				'onrowexit',
				'onrowsdelete',
				'onrowsinserted',
				'onscroll',
				'onselect',
				'onselectionchange',
				'onselectstart',
				'onstart',
				'onstop',
				'onsubmit',
				'onunload' 
		);
		$ra = array_merge ( $ra1, $ra2 );
		
		$found = true; // keep replacing as long as the previous round replaced something
		while ( $found == true ) {
			$val_before = $val;
			for($i = 0; $i < sizeof ( $ra ); $i ++) {
				$pattern = '/';
				for($j = 0; $j < strlen ( $ra [$i] ); $j ++) {
					if ($j > 0) {
						$pattern .= '(';
						$pattern .= '(&#[xX]0{0,8}([9ab]);)';
						$pattern .= '|';
						$pattern .= '|(&#0{0,8}([9|10|13]);)';
						$pattern .= ')*';
					}
					$pattern .= $ra [$i] [$j];
				}
				$pattern .= '/i';
				$replacement = substr ( $ra [$i], 0, 2 ) . '<x>' . substr ( $ra [$i], 2 ); // add in <> to nerf the tag
				$val = preg_replace ( $pattern, $replacement, $val ); // filter out the hex tags
				if ($val_before == $val) {
					// no replacements were made, so exit the loop
					$found = false;
				}
			}
		}
		return $val;
	}
}