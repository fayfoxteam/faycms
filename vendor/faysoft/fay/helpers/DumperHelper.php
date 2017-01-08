<?php
namespace fay\helpers;

class DumperHelper{
	private static $_output;
    private static $_depth;

	/**
	 * 相对于pr函数来说，dump更美观一些
	 * @param mixed $var 需要打印的变量
	 * @param int $depth 深度
	 * @return string
	 */
	public static function dump($var, $depth = 10){
		self::$_output = '';
		self::$_depth = $depth;
		self::dumpInternal($var);
		echo '<pre>', self::$_output, "\n</pre>";
	}

	/**
	 * 递归打印变量
	 * @param mixed $var 需要打印的变量
	 * @param int $level 深度
	 */
	private static function dumpInternal($var, $level = 0){
		switch (gettype($var)) {
			case 'boolean':
				self::$_output .= $var ? "<small>boolean</small> <font color=\"#75507b\">true</font>" : "<small>boolean</small> <font color=\"#75507b\">false</font>";
				break;
			case 'integer':
				self::$_output .= "<small>int</small> <font color=\"#4e9a06\">{$var}</font>";
				break;
			case 'double':
				self::$_output .= "<small>float</small> <font color=\"#f57900\">{$var}</font>";
				break;
			case 'string':
				self::$_output .= "<small>string</small> <font color=\"#cc0000\">'".htmlentities($var, ENT_QUOTES, 'UTF-8')."'</font> <i>(length=".mb_strlen($var, 'utf-8').")</i>";
				break;
			case 'resource':
				self::$_output .= '{resource}';
				break;
			case 'NULL':
				self::$_output .= "<font color=\"#3465a4\">null</font>";
				break;
			case 'unknown type':
				self::$_output .= '{unknown}';
				break;
			case 'array':
				if($level >= self::$_depth){
					self::$_output .= "<b>array</b>\n".str_repeat(' ', ($level + 1) * 4)."<i><font>[...]</font></i>";
				}else if(empty($var)){
					self::$_output .= "<b>array</b>\n".str_repeat(' ', ($level + 1) * 4)."<i><font color=\"#888a85\">empty</font></i>";
				}else{
					$keys = array_keys($var);
					$spaces = str_repeat(' ', ($level + 1) * 4);
					self::$_output .= "<b>array</b>";
					foreach ($keys as $key) {
						self::$_output .= "\n" . $spaces;
						if(is_numeric($key)){
							self::$_output .= $key;
						}else{
							self::$_output .= "'{$key}'";
						}
						self::$_output .= ' <font color="#888a85">=&gt;</font> ';
						self::dumpInternal($var[$key], $level + 1);
					}
				}
				break;
			case 'object':{
				$class_name = get_class($var);
				if($level >= self::$_depth){
					self::$_output .= "<b>object</b>(<i>".$class_name."</i>)\n".str_repeat(' ', ($level + 1) * 4)."<i><font>(...)</font></i>";
				}else{
					self::$_output .= "<b>object</b>(<i>".$class_name."</i>)";
					$spaces = str_repeat(' ', ($level + 1) * 4);
					foreach ((array) $var as $key => $value) {
						$key = trim($key);
						$pre = substr($key, 0, strpos($key, "\0"));
						if($pre == $class_name){
							//private
							self::$_output .= "\n{$spaces}<i>private</i> '".substr($key, strpos($key, "\0"))."'";
						}else if($pre == '*'){
							//protected
							self::$_output .= "\n{$spaces}<i>protected</i> '".substr($key, 1)."'";
						}else{
							//public
							self::$_output .= "\n{$spaces}<i>public</i> '{$key}'";
						}
						self::$_output .= ' <font color=\"#888a85\">=&gt;</font> ';
						self::dumpInternal($value, $level + 1);
					}
				}
				break;
			}
		}
	}

	/**
	 * 格式化输出一个变量
	 * @param array $var
	 * @param boolean $encode 若此参数为true，则会对数组内容进行html实体转换
	 * @param boolean $return 若此参数为true，则不直接输出数组，而是以变量的方式返回
	 * @return string
	 */
	public static function pr($var, $encode = false, $return = false){
		if($encode){
			$var = \F::input()->filterR('fay\helpers\HtmlHelper::encode', $var);
		}
		if($return){
			return '<pre>'.print_r($var, true).'</pre>';
		}else{
			echo '<pre>';
			print_r($var);
			echo '</pre>';
		}
	}
}