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
}