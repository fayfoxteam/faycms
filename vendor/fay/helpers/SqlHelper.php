<?php
namespace fay\helpers;

class SqlHelper{
	/**
	 * 简单的美化一下，必须结合faycms后台式样
	 * @param sql $sql SQL
	 * @param array $params 参数
	 */
	public static function nice($sql, $params = array()){
		$keywords = array('FROM', 'WHERE',
			'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN',
			'AS', 'LIMIT', 'ORDER BY', 'GROUP BY',
			'AND NOT', 'OR NOT', 'AND', 'OR', 'ON',
		);
		
		$pattern = array();
		foreach($keywords as $k){
			$pattern[] = "/({$k} )/i";
		}
		$sql = preg_replace($pattern, '<span class="fc-blue">$1</span>', $sql);
		
		$sql = preg_replace(array(
			'/^SELECT(.*)/i',
			'/^INSERT(.*)/i',
			'/^DELETE(.*)/i',
			'/^UPDATE(.*)/i',
		), array(
			'<span class="fc-blue">SELECT</span>$1',
			'<span class="fc-green">INSERT</span>$1',
			'<span class="fc-red">DELETE</span>$1',
			'<span class="fc-orange">UPDATE</span>$1',
		), $sql);
		
		if(!empty($params)){
			foreach($params as $p){
				$sql = preg_replace('/\?/', is_int($p) ? $p : "'".Html::encode($p)."'", $sql, 1);
			}
		}
		
		return $sql;
	}
	
	/**
	 * 将$sql中的问号替换为参数值，并将换行符移除
	 * @param string $sql SQL
	 * @param array $params 参数
	 */
	public static function bind($sql, $params = array()){
		$sql = str_replace("\n", ' ', $sql);
		$sql = preg_replace('/\s{2,}/', ' ', $sql);//这个替换实际上是有风险的，单引号内的空格也会被替换掉，只是暂时没找到更好的方法
		if(!empty($params)){
			foreach($params as $p){
				$sql = preg_replace('/\?/', is_int($p) ? $p : "'{$p}'", $sql, 1);
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
			if($prefix){
				//如果传入前缀，但前缀不符合的表将被舍弃
				if(strpos($table, $prefix) === 0){
					$return[] = substr($table, $prefix_length);
				}
			}else{
				//前缀为空，则不判断，直接返回
				$return[] = $table;
			}
		}
		return $return;
	}
}