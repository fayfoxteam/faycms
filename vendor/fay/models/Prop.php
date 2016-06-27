<?php
namespace fay\models;

use fay\core\ErrorException;
use fay\core\Model;
use fay\models\tables\Props;
use fay\models\tables\PropValues;
use fay\core\Sql;
use fay\helpers\StringHelper;
use fay\helpers\ArrayHelper;

abstract class Prop extends Model{
	/**
	 * 表模型，需要包含int，varchar，text3种类型
	 * 此类表必须包含3个字段：{$this->foreign_key}, prop_id, content
	 * 其中content字段类型分别为：int(10), varchar(255), text
	 * @var array
	 */
	protected $models;
	
	/**
	 * $this->models中表的外主键（例如文章附加属性，则对应外主键是文章ID：post_id）
	 * @var string
	 */
	protected $foreign_key;
	
	/**
	 * 类型
	 * @var int
	 */
	protected $type;
	
	public function __construct(){
		if(!$this->models){
			throw new ErrorException(__CLASS__ . '::$models属性未指定');
		}
		if(!$this->foreign_key){
			throw new ErrorException(__CLASS__ . '::$foreign_key属性未指定');
		}
		if(!$this->type){
			throw new ErrorException(__CLASS__ . '::$type属性未指定');
		}
	}
	
	/**
	 * @param $refer
	 * @param $prop
	 * @param array $values
	 * @return int
	 */
	public function create($refer, $prop, $values = array()){
		$prop_id = Props::model()->insert(array(
			'refer'=>$refer,
			'type'=>$this->type,
			'title'=>$prop['title'],
			'alias'=>$prop['alias'],
			'element'=>$prop['element'],
			'required'=>empty($prop['required']) ? 0 : 1,
			'is_show'=>isset($prop['is_show']) ? $prop['is_show'] : 1,
			'sort'=>isset($prop['sort']) ? $prop['sort'] : 0,
			'create_time'=>\F::app()->current_time,
		));
		
		if(in_array($prop['element'], array(
			Props::ELEMENT_RADIO,
			Props::ELEMENT_SELECT,
			Props::ELEMENT_CHECKBOX,
		))){
			//设置可选属性值
			$i = 0;
			foreach($values as $pv){
				$i++;
				PropValues::model()->insert(array(
					'prop_id'=>$prop_id,
					'title'=>$pv,
					'sort'=>$i,
					'refer'=>$refer,
				));
			}
		}
		
		return $prop_id;
	}
	
	/**
	 * 更新属性
	 * @param int $refer 引用（例如：角色ID，文章分类ID等）
	 * @param int $prop_id 属性ID
	 * @param array $prop 属性参数
	 * @param array $values 属性值
	 * @param array $ids 原属性ID数组，键值为空表示新增值
	 */
	public function update($refer, $prop_id, $prop, $values = array(), $ids = array()){
		$old_ids = array_filter($ids);
		$old_ids || $old_ids = array('-1');
		
		Props::model()->update($prop, $prop_id);
		
		//删除原有但现在没了的属性值
		PropValues::model()->update(array(
			'deleted'=>1,
		),array(
			'prop_id = ?'=>$prop_id,
			'id NOT IN ('.implode(',', $old_ids).')',
		));
		//设置属性值
		if(in_array($prop['element'], array(
			Props::ELEMENT_RADIO,
			Props::ELEMENT_SELECT,
			Props::ELEMENT_CHECKBOX,
		))){//手工录入属性没有属性值
			$i = 0;
			foreach($values as $k => $v){
				$i++;
				if(!empty($ids[$k])){
					PropValues::model()->update(array(
						'title'=>$v,
						'sort'=>$i,
					), array(
						'id = ?'=>$ids[$k],
					));
				}else{
					PropValues::model()->insert(array(
						'prop_id'=>$prop_id,
						'title'=>$v,
						'sort'=>$i,
						'refer'=>$refer,
					));
				}
			}
		}
	}
	
	public function delete($id){
		Props::model()->update(array(
			'deleted'=>1,
		), $id);
	}
	
	/**
	 * 获取一个属性，若其为可选属性，则同时获取所有可选项
	 * @param int $id
	 * @return array|bool
	 */
	public function get($id){
		$prop = Props::model()->fetchRow(array(
			'id = ?'=>$id,
			'deleted = 0',
		));
		
		if(!$prop) return array();
		
		if(in_array($prop['element'], array(
			Props::ELEMENT_RADIO,
			Props::ELEMENT_SELECT,
			Props::ELEMENT_CHECKBOX,
		))){
			$prop['values'] = PropValues::model()->fetchAll(array(
				'prop_id = ?'=>$prop['id'],
				'deleted = 0',
			), '*', 'sort');
		}
		
		return $prop;
	}
	
	/**
	 * 根据引用（例如：文章分类ID，用户角色ID）获取多个属性
	 * @param int|array $refer 引用ID或引用ID构成的一维数组
	 * @param bool $with_values 若为true，则附加属性可选值。默认为true
	 * @return array
	 */
	public function getByRefer($refer, $with_values = true){
		if(StringHelper::isInt($refer)){
			//获取单个属性
			$props = Props::model()->fetchAll(array(
				'refer = ?'=>$refer,
				'type = ' . $this->type,
				'deleted = 0',
			), 'id,title,type,required,element,alias', 'sort, id');
		}else if(!empty($refer)){
			//一次获取多个属性
			$props = Props::model()->fetchAll(array(
				'refer IN (?)'=>$refer,
				'type = ' . $this->type,
				'deleted = 0',
			), 'id,title,type,required,element,alias', 'sort, id');
		}else{
			return array();
		}
		
		//附加属性可选值
		if($with_values && $props){
			$props = $this->addValues($props);
		}
		
		return $props;
	}
	
	/**
	 * 获取一个或多个别名对应的属性
	 * @param array|string $props 属性别名或ID构成的一维数组或逗号分割字符串
	 *  以第一项为判断依据
	 *   - 若第一项是数字，视为id
	 *   - 若第一项不是数字，视为别名
	 * @param bool $with_values 若为true，则附加属性可选值。默认为true
	 * @return array
	 */
	public function mget($props, $with_values = true){
		if(!is_array($props)){
			$props = explode(',', $props);
		}
		
		if(StringHelper::isInt($props[0])){
			$field = 'id';
		}else{
			$field = 'alias';
		}
		
		if(isset($props[1])){
			//如果有多项，搜索条件用IN
			$props = Props::model()->fetchAll(array(
				"{$field} IN (?)"=>$props,
				'type = ' . $this->type,
				'deleted = 0',
			), 'id,title,type,required,element', 'sort,id');
		}else{
			//如果只有一项，搜索条件直接用等于
			$props = Props::model()->fetchAll(array(
				"{$field} = ?"=>$props[0],
				'type = ' . $this->type,
				'deleted = 0',
			), 'id,title,type,required,element', 'sort,id');
		}
		
		if($with_values && $props){
			//附加属性可选值
			$props = $this->addValues($props);
		}
		
		return $props;
	}
	
	/**
	 * 为props附加可选值
	 * @param array $props
	 * @return array
	 */
	private function addValues($props){
		//获取属性对应的可选属性值
		$prop_ids = ArrayHelper::column($props, 'id');
		if(isset($prop_ids[1])){
			$prop_values = PropValues::model()->fetchAll(array(
				'prop_id IN (?)'=>$prop_ids,
				'deleted = 0',
			), 'id,title,prop_id', 'prop_id,sort');
		}else{
			$prop_values = PropValues::model()->fetchAll(array(
				'prop_id = ?'=>$prop_ids,
				'deleted = 0',
			), 'id,title,prop_id', 'prop_id,sort');
		}
		foreach($props as &$p){
			//保证各项返回数据字段一致性，没有选项的输入类型也返回空的options
			$p['options'] = array();
			if(in_array($p['element'], array(
				Props::ELEMENT_RADIO,
				Props::ELEMENT_SELECT,
				Props::ELEMENT_CHECKBOX,
			))){
				$start = false;
				foreach($prop_values as $k => $v){
					if($v['prop_id'] == $p['id']){
						$p['options'][] = array(
							'id'=>$v['id'],
							'title'=>$v['title'],
						);
						$start = true;
						unset($prop_values[$k]);
					}else if($start){
						break;
					}
				}
			}
		}
		
		return $props;
	}
	
	/**
	 * 创建一个属性集
	 * @param int $refer $models中对应的字段值
	 * @param array $props 属性集合
	 * @param array $data 属性值，以属性集合的id为键的数组
	 */
	public function createPropertySet($refer, $props, $data){
		foreach($props as $p){
			switch($p['element']){
				case Props::ELEMENT_TEXT:
					\F::model($this->models['varchar'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$p['id'],
						'content'=>$data[$p['id']],
					));
					break;
				case Props::ELEMENT_RADIO:
					if(isset($data[$p['id']])){
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>intval($data[$p['id']]),
						));
					}
					break;
				case Props::ELEMENT_SELECT:
					if(!empty($data[$p['id']])){
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>intval($data[$p['id']]),
						));
					}
					break;
				case Props::ELEMENT_CHECKBOX:
					if(isset($data[$p['id']])){
						foreach($data[$p['id']] as $v){
							\F::model($this->models['int'])->insert(array(
								$this->foreign_key=>$refer,
								'prop_id'=>$p['id'],
								'content'=>intval($v),
							));
						}
					}
					break;
				case Props::ELEMENT_TEXTAREA:
					if(!empty($data[$p['id']])){
						\F::model($this->models['text'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>$data[$p['id']],
						));
					}
					break;
				case Props::ELEMENT_NUMBER:
					if(!empty($data[$p['id']])){
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>intval($data[$p['id']]),
						));
					}
					break;
				case Props::ELEMENT_IMAGE:
					if(!empty($data[$p['id']])){
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>intval($data[$p['id']]),
						));
					}
					break;
			}
		}
	}
	
	/**
	 * 获取一个属性集
	 * @param $refer
	 * @param $props
	 * @return array
	 */
	public function getPropertySet($refer, $props){
		$property_set = array();
		$sql = new Sql();
		foreach($props as $p){
			switch($p['element']){
				case Props::ELEMENT_TEXT:
					$value = \F::model($this->models['varchar'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($value){
						$p['value'] = $value['content'];
					}else{
						$p['value'] = '';
					}
					break;
				case Props::ELEMENT_RADIO:
					$value = $sql->from(array('pi'=>\F::model($this->models['int'])->getTableName()), '')
						->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
						->where(array(
							"pi.{$this->foreign_key} = ?"=>$refer,
							'pi.prop_id = ?'=>$p['id'],
						))
						->fetchRow()
					;
					$p['value'] = $value['id'];
					break;
				case Props::ELEMENT_SELECT:
					$value = $sql->from(array('pi'=>\F::model($this->models['int'])->getTableName()), '')
						->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
						->where(array(
							"pi.{$this->foreign_key} = ?"=>$refer,
							'pi.prop_id = ?'=>$p['id'],
						))
						->fetchRow()
					;
					$p['value'] = $value['id'];
					break;
				case Props::ELEMENT_CHECKBOX:
					$value = $sql->from(array('pi'=>\F::model($this->models['int'])->getTableName()), '')
						->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id')
						->where(array(
							"pi.{$this->foreign_key} = ?"=>$refer,
							'pi.prop_id = ?'=>$p['id'],
						))
						->fetchAll()
					;
					$p['value'] = implode(',', ArrayHelper::column($value, 'id'));
					break;
				case Props::ELEMENT_TEXTAREA:
					$value = \F::model($this->models['text'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($value){
						$p['value'] = $value['content'];
					}else{
						$p['value'] = '';
					}
					break;
				case Props::ELEMENT_NUMBER:
					$value = \F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($value){
						$p['value'] = $value['content'];
					}else{
						$p['value'] = '';
					}
					break;
				case Props::ELEMENT_IMAGE:
					$value = \F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($value){
						$p['value'] = $value['content'];
					}else{
						$p['value'] = '0';
					}
					break;
			}
			$property_set[] = $p;
		}
		return $property_set;
	}
	
	/**
	 * 更新一个属性集
	 * @param int $refer 字段值
	 * @param array $props 属性集合
	 * @param array $data 属性值
	 */
	public function updatePropertySet($refer, $props, $data){
		foreach($props as $p){
			switch($p['element']){
				case Props::ELEMENT_TEXT:
					//如果存在，且值有变化，则更新；不存在，则插入
					$record = \F::model($this->models['varchar'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($record){
						if($record['content'] != $data[$p['id']]){
							\F::model($this->models['varchar'])->update(array(
								'content'=>$data[$p['id']],
							), array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						\F::model($this->models['varchar'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>$data[$p['id']],
						));
					}
					break;
				case Props::ELEMENT_RADIO:
					$record = \F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if(empty($data[$p['id']])){
						//若无提交值，且原先有值，则删除以前的值
						if($record){
							\F::model($this->models['int'])->delete(array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						//如果存在，且值有变化，则更新；不存在，则插入
						if($record){
							if($record['content'] != $data[$p['id']]){
								\F::model($this->models['int'])->update(array(
									'content'=>intval($data[$p['id']]),
								), array(
									"{$this->foreign_key} = ?"=>$refer,
									'prop_id = ?'=>$p['id'],
								));
							}
						}else{
							\F::model($this->models['int'])->insert(array(
								$this->foreign_key=>$refer,
								'prop_id'=>$p['id'],
								'content'=>intval($data[$p['id']]),
							));
						}
					}
					break;
				case Props::ELEMENT_SELECT:
					$record = \F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if(empty($data[$p['id']])){
						//若无提交值，且原先有值，则删除以前的值
						if($record){
							\F::model($this->models['int'])->delete(array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						//如果存在，且值有变化，则更新；不存在，则插入
						if($record){
							if($record['content'] != $data[$p['id']]){
								\F::model($this->models['int'])->update(array(
									'content'=>intval($data[$p['id']]),
								), array(
									"{$this->foreign_key} = ?"=>$refer,
									'prop_id = ?'=>$p['id'],
								));
							}
						}else{
							\F::model($this->models['int'])->insert(array(
								$this->foreign_key=>$refer,
								'prop_id'=>$p['id'],
								'content'=>intval($data[$p['id']]),
							));
						}
					}
					break;
				case Props::ELEMENT_CHECKBOX:
					//获取已存在的项
					$old_options = \F::model($this->models['int'])->fetchCol('content', array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					));
					if(isset($data[$p['id']])){
						//删除已经不存在的项
						$delete_options = array_diff($old_options, $data[$p['id']]);
						if($delete_options){
							\F::model($this->models['int'])->delete(array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
								'content IN (?)'=>$delete_options,
							));
						}
						
						//插入新增项
						$new_options = array_diff($data[$p['id']], $old_options);
						if($new_options){
							foreach($new_options as $p_value){
								\F::model($this->models['int'])->insert(array(
									$this->foreign_key=>$refer,
									'prop_id'=>$p['id'],
									'content'=>intval($p_value),
								));
							}
						}
					}else{
						//若无提交值，且原先有值，则删除以前的值
						if($old_options){
							\F::model($this->models['int'])->delete(array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}
					break;
				case Props::ELEMENT_TEXTAREA:
					$record = \F::model($this->models['text'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					//如果存在，且值有变化，则更新；不存在，则插入
					if($record){
						if($record['content'] != $data[$p['id']]){
							\F::model($this->models['text'])->update(array(
								'content'=>$data[$p['id']],
							), array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						\F::model($this->models['text'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>$data[$p['id']],
						));
					}
					break;
				case Props::ELEMENT_NUMBER:
					//如果存在，且值有变化，则更新；不存在，则插入
					$record = \F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$p['id'],
					), 'content');
					if($record){
						if($record['content'] != $data[$p['id']]){
							\F::model($this->models['int'])->update(array(
								'content'=>$data[$p['id']],
							), array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$p['id'],
							'content'=>$data[$p['id']],
						));
					}
					break;
				case Props::ELEMENT_IMAGE:
					if(empty($data[$p['id']])){
						//若没有传值过来或传了空值，且原先有记录，则删除记录
						if(\F::model($this->models['int'])->fetchRow(array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$p['id'],
						), 'content')){
							\F::model($this->models['int'])->delete(array(
								"{$this->foreign_key} = ?"=>$refer,
								'prop_id = ?'=>$p['id'],
							));
						}
					}else{
						//如果存在，且值有变化，则更新；不存在，则插入
						$record = \F::model($this->models['int'])->fetchRow(array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$p['id'],
						), 'content');
						if($record){
							if($record['content'] != $data[$p['id']]){
								\F::model($this->models['int'])->update(array(
									'content'=>$data[$p['id']],
								), array(
									"{$this->foreign_key} = ?"=>$refer,
									'prop_id = ?'=>$p['id'],
								));
							}
						}else{
							\F::model($this->models['int'])->insert(array(
								$this->foreign_key=>$refer,
								'prop_id'=>$p['id'],
								'content'=>$data[$p['id']],
							));
						}
					}
					break;
			}
		}
	}
	
	/**
	 * 根据属性别名，单一更新一个属性的属性值
	 * @param string $alias 属性别名
	 * @param mixed $value 属性值
	 * 若属性元素对应的是输入框，文本域或单选框，则直接更新属性值
	 * 若属性元素对应的是多选框：
	 *  - 当$value是数字的时候，仅做插入（已存在则无操作）操作，
	 *  - 当$value是数组的时候，将影响原有的属性值（不存在则删除，已存在则无操作）
	 * @param int $refer 引用值（例如：文章ID，用户ID）
	 * @return bool
	 */
	public function setValue($alias, $value, $refer){
		$prop = Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), 'id,element');
		if(!$prop) return false;
		
		switch($prop['element']){
			case Props::ELEMENT_RADIO:
			case Props::ELEMENT_SELECT:
				$record = \F::model($this->models['int'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				));
				if($record){
					if($record['content'] != $value){
						\F::model($this->models['int'])->update(array(
							'content'=>intval($value),
						), array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$prop['id'],
						));
					}
				}else{
					\F::model($this->models['int'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$prop['id'],
						'content'=>intval($value),
					));
				}
				break;
			case Props::ELEMENT_CHECKBOX:
				if(is_array($value)){//$value是数组，完整更新
					//删除已经不存在的项
					\F::model($this->models['int'])->delete(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$prop['id'],
						'content NOT IN ('.implode(',', \F::filter('intval', $value)).')',
					));
					//获取已存在的项
					$old_options = \F::model($this->models['int'])->fetchCol('content', array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$prop['id'],
					));
					//插入新增项
					foreach($value as $p_value){
						if(!in_array($p_value, $old_options)){
							\F::model($this->models['int'])->insert(array(
								$this->foreign_key=>$refer,
								'prop_id'=>$prop['id'],
								'content'=>intval($p_value),
							));
						}
					}
				}else{//$value不是数组，仅更新一个属性值选项
					if(\F::model($this->models['int'])->fetchRow(array(
						"{$this->foreign_key} = ?"=>$refer,
						'prop_id = ?'=>$prop['id'],
					))){
						\F::model($this->models['int'])->update(array(
							'content'=>intval($value),
						), array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$prop['id'],
						));
					}else{
						\F::model($this->models['int'])->insert(array(
							$this->foreign_key=>$refer,
							'prop_id'=>$prop['id'],
							'content'=>intval($value),
						));
					}
				}
				break;
			case Props::ELEMENT_TEXT:
				//如果存在，且值有变化，则更新；不存在，则插入
				$record = \F::model($this->models['text'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				));
				if($record){
					if($record['content'] != $value){
						\F::model($this->models['varchar'])->update(array(
							'content' => $value,
						), array(
							"{$this->foreign_key} = ?" => $refer,
							'prop_id = ?' => $prop['id'],
						));
					}
				}else{
					\F::model($this->models['varchar'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$prop['id'],
						'content'=>$value,
					));
				}
				break;
			case Props::ELEMENT_TEXTAREA:
				//如果存在，且值有变化，则更新；不存在，则插入
				$record = \F::model($this->models['text'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				));
				if($record){
					if($record['content'] != $value){
						\F::model($this->models['text'])->update(array(
							'content' => $value,
						), array(
							"{$this->foreign_key} = ?" => $refer,
							'prop_id = ?' => $prop['id'],
						));
					}
				}else{
					\F::model($this->models['text'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$prop['id'],
						'content'=>$value,
					));
				}
				break;
			case Props::ELEMENT_NUMBER:
				//如果存在，且值有变化，则更新；不存在，则插入
				$record = \F::model($this->models['int'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				));
				if($record){
					if($record['content'] != $value){
						\F::model($this->models['int'])->update(array(
							'content'=>$value,
						), array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$prop['id'],
						));
					}
				}else{
					\F::model($this->models['int'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$prop['id'],
						'content'=>$value,
					));
				}
				break;
			case Props::ELEMENT_IMAGE:
				//如果存在，且值有变化，则更新；不存在，则插入
				$record = \F::model($this->models['int'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				));
				if($record){
					if($record['content'] != $value){
						\F::model($this->models['int'])->update(array(
							'content'=>$value,
						), array(
							"{$this->foreign_key} = ?"=>$refer,
							'prop_id = ?'=>$prop['id'],
						));
					}
				}else{
					\F::model($this->models['int'])->insert(array(
						$this->foreign_key=>$refer,
						'prop_id'=>$prop['id'],
						'content'=>$value,
					));
				}
				break;
		}
		
		return true;
	}
	
	/**
	 * 获取一个用户属性值
	 * @param string $alias
	 * @param int $refer 引用值（例如：文章ID，用户ID）
	 * @return mixed
	 */
	public function getValue($alias, $refer){
		$prop = Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), 'id,element');
		if(!$prop) return false;
		
		$sql = new Sql();
		switch($prop['element']){
			case Props::ELEMENT_TEXT:
				$value = \F::model($this->models['varchar'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				), 'content');
				if($value){
					return $value['content'];
				}else{
					return '';
				}
			case Props::ELEMENT_RADIO:
				return $sql->from(array('pi'=>\F::model($this->models['int'])->getTableName()), '')
					->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
					->where(array(
						"pi.{$this->foreign_key} = ?"=>$refer,
						'pi.prop_id = ?'=>$prop['id'],
					))
					->fetchRow()
				;
			case Props::ELEMENT_SELECT:
			case Props::ELEMENT_CHECKBOX:
				return $sql->from(array('pi'=>\F::model($this->models['int'])->getTableName()), '')
					->joinLeft(array('v'=>'prop_values'), 'pi.content = v.id', 'id,title')
					->where(array(
						"pi.{$this->foreign_key} = ?"=>$refer,
						'pi.prop_id = ?'=>$prop['id'],
					))
					->fetchAll()
				;
			case Props::ELEMENT_TEXTAREA:
				$value = \F::model($this->models['text'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				), 'content');
				if($value){
					return $value['content'];
				}else{
					return '';
				}
			case Props::ELEMENT_NUMBER:
				$value = \F::model($this->models['int'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				), 'content');
				if($value){
					return $value['content'];
				}else{
					return '';
				}
			case Props::ELEMENT_IMAGE:
				$value = \F::model($this->models['int'])->fetchRow(array(
					"{$this->foreign_key} = ?"=>$refer,
					'prop_id = ?'=>$prop['id'],
				), 'content');
				if($value){
					return $value['content'];
				}else{
					return '0';
				}
			default:
				return '';
		}
	}
	
	/**
	 * 根据属性别名，获取可选的属性值
	 * @param $alias
	 * @return array|bool
	 */
	public function getPropOptionsByAlias($alias){
		$prop = Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'deleted = 0',
		), 'id');
		if($prop){
			return PropValues::model()->fetchAll(array(
				'prop_id = '.$prop['id'],
				'deleted = 0',
			), 'id,title,default', 'sort');
		}else{
			return false;
		}
	}
	
	/**
	 * 根据属性别名，获取属性ID
	 * @param string $alias
	 * @return int
	 */
	public function getIdByAlias($alias){
		$prop = Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), 'id');
		return $prop['id'];
	}
}