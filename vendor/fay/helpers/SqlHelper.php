<?php
namespace fay\helpers;

class SqlHelper{
	/**
	 * 简单的美化一下，必须结合faycms后台式样
	 * @param sql $sql
	 */
	public static function nice($sql, $params){
		$keywords = array('FROM', 'WHERE',
			'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN',
			'AS', 'LIMIT', 'ORDER BY', 'GROUP BY',
			'AND NOT', 'OR NOT', 'AND', 'OR', 'ON',
		);
		
		$pattern = array();
		foreach($keywords as $k){
			$pattern[] = "/({$k} )/i";
		}
		$sql = preg_replace($pattern, '<span class="color-blue">$1</span>', $sql);
		
		$sql = preg_replace(array(
			'/^SELECT(.*)/i',
			'/^INSERT(.*)/i',
			'/^DELETE(.*)/i',
			'/^UPDATE(.*)/i',
		), array(
			'<span class="color-blue">SELECT</span>$1',
			'<span class="color-green">INSERT</span>$1',
			'<span class="color-red">DELETE</span>$1',
			'<span class="color-orange">UPDATE</span>$1',
		), $sql);
		
		if(!empty($params)){
			foreach($params as $p){
				$sql = preg_replace('/\?/', is_numeric($p) ? $p : "'".Html::encode($p)."'", $sql, 1);
			}
		}
		
		return $sql;
	}
	
	/**
	 * 移除数组统一的前缀（前缀不符合的表将被舍弃）
	 * 返回的数组会被重新索引
	 */
	public static function removePrefix($prefix, $tables){
		$prefix_length = strlen($prefix);
		$return = array();
		foreach($tables as $t){
			$table = array_shift($t);
			//前缀不符合的表将被舍弃
			if(strpos($table, $prefix) === 0){
				$return[] = substr($table, $prefix_length);
			}
		}
		return $return;
	}
}