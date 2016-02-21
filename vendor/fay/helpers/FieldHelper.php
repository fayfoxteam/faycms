<?php
namespace fay\helpers;

class FieldHelper{
	/**
	 * 将users.username,users.nickname,users.id,props.*,users.role.*这样的字符串，
	 * 转换为如下格式的数组
	 * array(
	 *   'users'=>array(
	 *     'username', 'nickname', 'id', 'role.*'
	 *   ),
	 *   'props'=>array(
	 *     '*',
	 *   ),
	 * )
	 * 只会切割第一个点，后面的点不会被分割
	 * @param string $fields
	 * @param string|null $default_key 若设置了default_key，则不包含.(点号)的项会被归属到default_key下
	 * @param array $allowed_fields 若该字段非空，则会调用self::filter方法对解析后的$fields进行过滤
	 */
	public static function process($fields, $default_key = null, $allowed_fields = array()){
		if(is_array($fields)){
			//如果已经是数组，则直接返回（防止重复调用）
			return $fields;
		}
		$fields = explode(',', $fields);
		$return = array();
		foreach($fields as $f){
			$f = trim($f);
			if(strpos($f, '.')){
				$fa = explode('.', $f, 2);
				$return[$fa[0]][] = $fa[1];
			}else if(!empty($f)){
				if($default_key){
					$return[$default_key][] = $f;
				}else{
					$return[] = $f;
				}
			}
		}
		
		if($allowed_fields){
			return self::filter($return, $allowed_fields);
		}else{
			return $return;
		}
	}
	
	/**
	 * 从$fields中过滤出被允许的字段
	 * @param array $fields 解析成数组后的用户指定字段
	 * @param array $allowed_fields 允许的字段
	 */
	public static function filter($fields, $allowed_fields){
		foreach($fields as $k => $v){
			if(!isset($allowed_fields[$k])){
				unset($fields[$k]);
				continue;
			}
			$fields[$k] = in_array('*', $v) ? $allowed_fields[$k] : array_intersect($allowed_fields[$k], $v);
		}
		
		return $fields;
	}
}