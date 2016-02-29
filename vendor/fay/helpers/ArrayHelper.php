<?php
namespace fay\helpers;

class ArrayHelper{
	/**
	 * php5.5以下版本没有array_column函数<br>
	 * 此方法用于兼容低版本
	 * @param array $array
	 * @param string $column_key
	 * @return array
	 */
	public static function column($array, $column_key, $index_key = null){
		if(function_exists('array_column')){
			return array_column($array, $column_key, $index_key);
		}else{
			if($column_key === null && $index_key === null){
				return $array;
			}else{
				$return = array();
				foreach($array as $a){
					if($index_key !== null){
						if($column_key === null){
							$return[$a[$index_key]] = $a;
						}else{
							$return[$a[$index_key]] = $a[$column_key];
						}
					}else{
						$return[] = $a[$column_key];
					}
				}
				return $return;
			}
		}
	}
	
	/**
	 * 多维数组取交集，返回在$array和$array2中都存在的项
	 * @param array $array
	 * @param array $array2
	 */
	public static function intersect($array, $array2){
		foreach($array as $k => $a){
			if(is_array($a)){
				//是数组
				if(isset($array2[$k])){
					//$array2中存在这个key，递归
					$array[$k] = self::intersect($a, $array2[$k]);
				}else{
					//$array2中不存在这个key，直接unset
					unset($array[$k]);
				}
			}else{
				//不是数组
				if(!in_array($a, $array2)){
					unset($array[$k]);
				}
			}
		}
		
		return $array;
	}
}