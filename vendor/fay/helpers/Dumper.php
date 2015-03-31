<?php
namespace fay\helpers;

/**
 * 从yii抄袭来的Dump类
 */
class Dumper
{
	private static $_objects;
	private static $_output;
	private static $_depth;
	
	public static function dump($var, $depth = 10, $highlight = true)
	{
		self::$_output = '';
		self::$_objects = array();
		self::$_depth = $depth;
		self::dumpInternal($var, 0);
		if ($highlight) {
			$result = highlight_string("<?php\n" . self::$_output, true);
			echo preg_replace('/&lt;\\?php<br \\/>/', '', $result, 1);
		}else{
			echo '<pre>', self::$_output, '</pre>';
		}
	}
	
	private static function dumpInternal($var, $level)
	{
		switch (gettype($var)) {
			case 'boolean':
				self::$_output .= $var ? 'true' : 'false';
				break;
			case 'integer':
				self::$_output .= "$var";
				break;
			case 'double':
				self::$_output .= "$var";
				break;
			case 'string':
				self::$_output .= "'" . addslashes($var) . "'";
				break;
			case 'resource':
				self::$_output .= '{resource}';
				break;
			case 'NULL':
				self::$_output .= "null";
				break;
			case 'unknown type':
				self::$_output .= '{unknown}';
				break;
			case 'array':
				if (self::$_depth <= $level) {
					self::$_output .= 'array(...)';
				} elseif (empty($var)) {
					self::$_output .= 'array()';
				} else {
					$keys = array_keys($var);
					$spaces = str_repeat(' ', ($level + 1) * 4);
					self::$_output .= 'array(';
					foreach ($keys as $key) {
						self::$_output .= "\n" . $spaces . '    ';
						self::dumpInternal($key, 0);
						self::$_output .= ' => ';
						self::dumpInternal($var[$key], $level + 1);
					}
					self::$_output .= "\n" . $spaces . ')';
				}
				break;
			case 'object':
				if (($id = array_search($var, self::$_objects, true)) !== false) {
					self::$_output .= get_class($var) . '#' . ($id + 1) . '(...)';
				} elseif (self::$_depth <= $level) {
					self::$_output .= get_class($var) . '(...)';
				} else {
					$id = array_push(self::$_objects, $var);
					$className = get_class($var);
					$spaces = str_repeat(' ', ($level + 1) * 4);
					self::$_output .= "$className#$id\n" . $spaces . '(';
					foreach ((array) $var as $key => $value) {
						$keyDisplay = strtr(trim($key), array("\0" => ':'));
						self::$_output .= "\n" . $spaces . "	[$keyDisplay] => ";
						self::dumpInternal($value, $level + 1);
					}
					self::$_output .= "\n" . $spaces . ')';
				}
				break;
		}
	}
}
