<?php
namespace fay\helpers;

class FieldHelper{
	/**
	 * 将user.username,user.nickname,user.id,user.avatar:320x320,props.*,user.role.id,user.role.title这样的字符串，
	 * 转换为如下格式的数组
	 * array(
	 *   'user'=>array(
	 *     'username', 'nickname', 'id', 'role'=>array(
	 *       'id', 'title'
	 *     )
	 *   ),
	 *   'props'=>array(
	 *     '*'
	 *   ),
	 *   '_extra'=>array(
	 *     'user'=>array(
	 *       'avatar'=>'320x320'
	 *     )
	 *   )
	 * )
	 * @param string $fields
	 * @param string|null $default_key 若设置了$default_key，则不包含.(点号)的项会被归属到$default_key下
	 * @param array $allowed_fields 若该字段非空，则会调用self::filter()方法对解析后的$fields进行过滤
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
			foreach($fields as $field){
				$field = trim($field);
				if(strpos($field, '.')){
					//如果带有点号，则归属到指定的数组项
					$field_path = explode('.', $field);
					$field = array_pop($field_path);//最后一项是字段值
					
					if(strpos($field, ':')){
						//若存在冒号，则有附加信息
						$field_extra = explode(':', $field, 2);
						$field = $field_extra[0];
						if(isset($field_path[1])){
							//字段路径大于1个时，插入到倒数第二个层级
							$field_path_copy = $field_path;
							$parent_path = array_pop($field_path_copy);
							eval('$return[\'' . implode("']['", $field_path_copy) . "']['_extra']['{$parent_path}']['{$field}']='{$field_extra[1]}';");
						}else{
							//字段路径只有1个，插入到顶级
							$return['_extra'][$field_path[0]][$field] = $field_extra[1];
						}
					}
					
					eval('$return[\'' . implode("']['", $field_path) . "'][]='{$field}';");
				}else if(!empty($field)){
					//没有点好，且非空，则归属到顶级或默认键值下
					if(strpos($field, ':')){
						//若存在冒号，则有附加信息
						$field_extra = explode(':', $field, 2);
						$field = $field_extra[0];
						if($default_key){
							$return['_extra'][$default_key][$field] = $field_extra[1];
						}else{
							$return['_extra'][$field] = $field_extra[1];
						}
					}
					if($default_key){
						$return[$default_key][] = $field;
					}else{
						$return[] = $field;
					}
				}
			}
			
			return $allowed_fields ? self::filter($return, $allowed_fields) : $return;
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
			if($k == '_extra'){
				//_extra是系统生成的扩展信息，不过滤
				continue;
			}
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