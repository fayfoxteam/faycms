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
	
	/**
	 * （严格来说这不算是一个sql方法，只是写法上像sql的fields）
	 * 将users.username,users.nickname,users.id,props.*这样的字符串，
	 * 转换为如下格式的数组
	 * array(
	 *   'users'=>array(
	 *     'username', 'nickname', 'id',
	 *   ),
	 *   'props'=>array(
	 *     '*',
	 *   ),
	 * )
	 * @param string $fields
	 * @param string|null $default_key 若设置了default_key，则不包含.(点号)的项会被归属到default_key下
	 */
	public static function processFields($fields, $default_key = null){
		$fields = explode(',', $fields);
		$return = array();
		foreach($fields as $f){
			$f = trim($f);
			if(strpos($f, '.')){
				$fa = explode('.', $f);
				$fa_end = array_pop($fa);
				eval('$return[\'' . implode("']['", $fa) . "'][]='{$fa_end}';");
			}else if(!empty($f)){
				if($default_key){
					$return[$default_key][] = $f;
				}else{
					$return[] = $f;
				}
			}
		}
		return $return;
	}
}