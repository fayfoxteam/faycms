<?php
namespace fay\helpers;

class FieldHelper{
	/**
	 * 将users.username,users.nickname,users.id,props.*,users.role.id,users.role.title这样的字符串，
	 * 转换为如下格式的数组
	 * array(
	 *   'users'=>array(
	 *     'username', 'nickname', 'id', 'role'=>array(
	 *       'id', 'title',
	 *     ),
	 *   ),
	 *   'props'=>array(
	 *     '*',
	 *   ),
	 * )
	 * @param string $fields
	 * @param string|null $default_key 若设置了default_key，则不包含.(点号)的项会被归属到default_key下
	 * @param array $allowed_fields 若该字段非空，则会调用self::filter方法对解析后的$fields进行过滤
	 * @return array
	 */
	public static function process($fields, $default_key = null, $allowed_fields = array()){
		if(is_array($fields) && $default_key){
			//如果已经是数组，且有$default_key，则把索引数组项归类到$default_key下（当传入$fields是二次解析的数组时，可能存在此类情况）
			foreach($fields as $k => $f){
				if(is_int($k)){
					$fields[$default_key][] = $f;
					unset($fields[$k]);
				}
			}
			return $fields;
		}else{
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
			
			if($allowed_fields){
				return self::filter($return, $allowed_fields);
			}else{
				return $return;
			}
		}
	}
	
	/**
	 * 从$fields中过滤出被允许的字段
	 * @param array $fields 解析成数组后的用户指定字段
	 * @param array $allowed_fields 允许的字段
	 * @return array
	 */
	public static function filter($fields, $allowed_fields){
		foreach($fields as $k => $v){
			if(!isset($allowed_fields[$k])){
				unset($fields[$k]);
				continue;
			}
			if(in_array('*', $v)){
				//若获取字段中包含*，则返回所有允许的字段
				$fields[$k] = $allowed_fields[$k];
			}else if(in_array('*', $allowed_fields[$k])){
				//若允许的字段中包含*，则返回所有用户指定字段
				$fields[$k] = $v;
			}else{
				//否则做将用户字段与允许的字段做交集
				$fields[$k] = ArrayHelper::intersect($allowed_fields[$k], $v);
			}
		}
		
		return $fields;
	}
	
	/**
	 * 将self::process()解析出来的字符串拼凑回去
	 * @param array $data self::process()得到的结果
	 * @param string $prefix 前缀
	 * @return string
	 */
	public static function join($data, $prefix = ''){
		$return = array();
		foreach($data as $key => $fields){
			if(is_int($key)){
				//取process中的一部分的时候，会出现这种情况
				$return[] = $prefix ? "{$prefix}.{$fields}" : $fields;
			}else{
				foreach($fields as $k=>$f){
					if(is_int($k)){
						$return[] = $prefix ? "{$prefix}.{$key}.{$f}" : "{$key}.{$f}";
					}else{
						$return[] = self::join(array($k=>$f), $prefix ? "{$prefix}.{$key}" : $key);
					}
				}
			}
		}
		
		return implode(',', $return);
	}
}