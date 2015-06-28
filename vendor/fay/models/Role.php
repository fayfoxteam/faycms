<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\models\tables\PropValues;

class Role extends Model{
	/**
	 * @return Role
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 获取一个角色，fields可指定是否返回角色附加属性
	 * @param int $id
	 * @param string $fields
	 */
	public function get($id, $fields = 'props'){
		$fields = explode(',', $fields);
		$role = Roles::model()->find($id);
		
		if(!$role){
			return false;
		}
		
		if(in_array('props', $fields)){
			//属性
			$role['props'] = Props::model()->fetchAll(array(
				'refer = ?'=>$id,
				'type = '.Props::TYPE_ROLE,
				'deleted = 0',
			), 'id,title,element,required', 'sort, id');
			
			$prop_values = PropValues::model()->fetchAll(array(
				'refer = ?'=>$id,
				'deleted = 0',
			), 'id,title,prop_id', 'prop_id, sort');
			foreach($role['props'] as &$p){
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
		
		return $role;
	}
}