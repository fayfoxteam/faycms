<?php
namespace doc\helpers;

class CodeFile{
	//根据函数名获取该函数定义在所给定文件的第几行
	public static function getLineByFunctionName($function, $file){
		if(file_exists($file)){
			$lines = file($file);
			foreach($lines as $k => $v){
				if(strpos($v, "function {$function}(") != false){
					return $k;
				}
			}
		}
		return false;
	}
}