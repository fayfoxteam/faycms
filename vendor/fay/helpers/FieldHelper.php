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
	public static function parse($fields, $default_key = 'default', $allowed_fields = array()){
		if(is_array($fields)){
			$return = self::_parseArray($fields, $default_key);
		}else{
			//解析字符串
			$return = self::_parseString($fields, $default_key);
		}
		
		return $allowed_fields ? self::filter($return, $allowed_fields) : $return;
	}
	
	/**
	 * 将字符串解析为数组
	 * //post.id,post.thumbnail:320x320,user.id,user.avatar:200x200,user.roles.id
	 * array(
	 *   'post'=>array(
	 *     'fields'=>array('id', 'thumbnail'),
	 *     'extra'=>array(
	 *       'thumbnail'=>'320x320',
	 *     )
	 *   ),
	 *   'user'=>array(
	 *     'fields'=>array(
	 *       'id',
	 *       'avatar',
	 *       'roles'=>array(
	 *         'fields'=>array('id')
	 *       )
	 *     ),
	 *     'extra'=>array(
	 *       'avatar'=>'200x200',
	 *     )
	 *   )
	 * )
	 * @param string $string
	 * @param string $default_key
	 * @return array
	 */
	private static function _parseString($string, $default_key){
		$fields = explode(',', $string);
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
					
					eval('$return[\'' . implode("']['fields']['", $field_path) . "']['extra']['$field']='{$field_extra[1]}';");
				}
				
				eval('$return[\'' . implode("']['fields']['", $field_path) . "']['fields'][]='{$field}';");
			}else if(!empty($field)){
				//没有点好，且非空，则归属到顶级或默认键值下
				if(strpos($field, ':')){
					//若存在冒号，则有附加信息
					$field_extra = explode(':', $field, 2);
					$field = $field_extra[0];
					$return[$default_key]['extra'][$field] = $field_extra[1];
				}
				$return[$default_key]['fields'][] = $field;
			}
		}
		
		return $return;
	}
	
	/**
	 * 将数组（外层$fields解析后得到的）转换为标准结构
	 * @param array $array
	 * @param string $default_key
	 * @return array
	 */
	private static function _parseArray($array, $default_key){
		$return = array();
		foreach($array['fields'] as $k => $field){
			if(is_int($k)){
				$return[$default_key]['fields'][] = $field;
			}else{
				$return[$k] = $field;
			}
		}
		
		if(isset($array['extra'])){
			$return[$default_key]['extra'] = $array['extra'];
		}
		
		return $return;
	}
	
	/**
	 * 从$fields中过滤出被允许的字段
	 * @param array $fields 解析成数组后的用户指定字段
	 * @param array $allowed_fields 允许的字段
	 * @return array
	 */
	public static function filter($fields, $allowed_fields){
		foreach($fields as $k => $v){
			if(is_array($v)){
				if(!isset($allowed_fields[$k])){
					//如果键在允许字段中都不存在，直接删除该键
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
					//两边都没有星号，递归判断是否允许
					$fields[$k] = self::filter($v, $allowed_fields[$k]);
				}
			}else{
				//值不是数组，判断是否允许该字段
				if(!in_array($v, $allowed_fields)){
					unset($fields[$k]);
				}
			}
		}
		return $fields;
	}
	
	/**
	 * 将self::parse()解析出来的字符串拼凑回去
	 * @param array $data self::parse()得到的结果
	 * @param string $prefix 前缀
	 * @return string
	 */
	public static function build($data, $prefix = ''){
		$return = array();
		foreach($data as $sk => $section){
			foreach($section['fields'] as $fk => $field){
				if(is_int($fk)){
					$field_str = $prefix ? "{$prefix}.{$sk}.{$field}" : "{$sk}.{$field}";
					if(isset($section['extra'][$field])){
						$field_str.= ":{$section['extra'][$field]}";
					}
					$return[] = $field_str;
				}else{
					$return[] = self::build(
						array($fk=>$field),
						$prefix ? "{$prefix}.{$sk}" : "{$sk}"
					);
				}
			}
		}
		
		return implode(',', $return);
	}
}