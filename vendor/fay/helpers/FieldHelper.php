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
	 */
	public static function process($fields, $default_key = null){
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
		return $return;
	}
}