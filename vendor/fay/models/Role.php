<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\models\tables\PropValues;
use fay\helpers\FieldHelper;

class Role extends Model{
	/**
	 * @return Role
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一个或多个角色，fields可指定是否返回角色附加属性
	 * @param int|array|string $ids 可以是数字id，或id组成的数组，或逗号分割的id字符串
	 * @param string $fields
	 */
	public function get($ids, $fields = 'roles.*,props.*'){
		$fields = FieldHelper::process($fields, 'roles');
		
		if(is_array($ids)){
			$ids = implode(',', $ids);
		}
		
		if(empty($ids)){
			return array();
		}
		
		$roles = Roles::model()->fetchAll("id IN ({$ids})", !empty($fields['roles']) ? $fields['roles'] : '*');
		
		if(!$roles){
			return array();
		}
		
		if(isset($fields['props'])){
			//属性
			$roles['props'] = Props::model()->fetchAll(array(
				"refer IN ({$ids})",
				'type = '.Props::TYPE_ROLE,
				'deleted = 0',
				'alias IN (?)'=>in_array('*', $fields['props']) ? false : $fields['props'],
			), 'id,title,element,required', 'sort, id');
			
			if($roles['props']){
				$prop_values = PropValues::model()->fetchAll(array(
					"refer IN ({$ids})",
					'deleted = 0',
				), 'id,title,prop_id', 'prop_id, sort');
				foreach($roles['props'] as &$p){
					if(in_array($p['element'], array(
						Props::ELEMENT_RADIO,
						Props::ELEMENT_SELECT,
						Props::ELEMENT_CHECKBOX,
					))){
						$start = false;
						foreach($prop_values as $k => $v){
							if($v['prop_id'] == $p['id']){
								$p['values'][$v['id']] = $v['title'];
								$start = true;
								unset($prop_values[$k]);
							}else if($start){
								break;
							}
						}
					}
				}
			}
		}
		
		return $roles;
	}
}