<?php
namespace fay\helpers;

class ArrayHelper{
	/**
	 * php5.5以下版本没有array_column函数<br>
	 * 此方法用于兼容低版本
	 * @param array $array
	 * @param string $column_key
	 * @param mix $index_key
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
	 * array_intersect()函数并不支持多维数组的交集，且是强类型比较，但支持n个参数。
	 * 此函数支持多维数组，且是若类型比较，但只能传2个参数
	 * @param array $array
	 * @param array $array2
	 * @return array
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

	/**
	 * 递归合并2个数组
	 *  - 数组键是int
	 *    * 数组2的值追加到数组1上
	 *  - 数组键不是int
	 *    * 数组1对应键存在且值是数组，则合并数组值
	 *    * 数组1对应键存在且值不是数组，数组2的值覆盖数组1的值
	 *    * 数组1对应键不存在，数组2的值追加到数组1上
	 * array_merge_recursive 会把字符串的值强制转为数组，然后对数组合并，对重复的值会重复出现
	 * 而此函数对字符串的值做覆盖处理，重复的值不会重复出现
	 * @param array $array
	 * @param array $array2
	 * @return array
	 */
	public static function merge($array, $array2){
		foreach($array2 as $k => $v){
			if(is_int($k)){
				//键是数字，且值不存在，追加到数组后面即可（这个情况在本系统中并不常见）
				if(!in_array($v, $array)){
					$array[] = $v;
				}
			}else if(isset($array[$k])){
				//键非数字，且键存在
				if(is_array($array[$k])){
					$array[$k] = self::merge($array[$k], (array)$v);
				}else{
					$array[$k] = $v;
				}
			}else{
				//键非数字，且键不存在，追加到数组中
				$array[$k] = $v;
			}
		}
		
		return $array;
	}
}