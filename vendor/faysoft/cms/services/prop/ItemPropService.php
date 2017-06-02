<?php
namespace cms\services\prop;

use cms\models\tables\PropsTable;
use cms\services\prop\elements\ElementAbstract;
use fay\core\ErrorException;
use fay\helpers\ArrayHelper;

/**
 * 单体自定义属性服务
 * 例如：某篇文章的自定义属性设置，获取。
 */
class ItemPropService{
    /**
     * @var array 表单元素对应类
     */
    public static $elementMap = array(
        PropsTable::ELEMENT_TEXT => 'cms\services\prop\elements\PropElementText',
        PropsTable::ELEMENT_RADIO => 'cms\services\prop\elements\PropElementRadio',
        PropsTable::ELEMENT_SELECT => 'cms\services\prop\elements\PropElementSelect',
        PropsTable::ELEMENT_CHECKBOX => 'cms\services\prop\elements\PropElementCheckbox',
        PropsTable::ELEMENT_TEXTAREA => 'cms\services\prop\elements\PropElementTextarea',
        PropsTable::ELEMENT_NUMBER => 'cms\services\prop\elements\PropElementNumber',
        PropsTable::ELEMENT_IMAGE => 'cms\services\prop\elements\PropElementImage',
    );

    /**
     * @var array 表单元素实例（相当于单例，避免重复实例化）
     */
    protected $elementInstances = array();

    /**
     * @var int （例如：文章ID，用户ID等）
     */
    protected $relation_id;
    
    /**
     * @var PropUsageInterface 与属性类型相关的信息获取
     */
    protected $usage_model;

    /**
     * @param int $relation_id （例如：文章ID，用户ID等）
     * @param PropUsageInterface $usage_model
     */
    public function __construct($relation_id, PropUsageInterface $usage_model){
        $this->relation_id = $relation_id;
        $this->usage_model = $usage_model;
    }

    /**
     * 创建一个属性集
     * @param array $props 属性集合
     * @param array $data 属性值，以属性集合的id为键的数组
     * @param array $labels 自定义属性名称
     */
    public function createPropSet($props, array $data, $labels = array()){
        foreach($props as $prop){
            $this->getElement($prop['element'])->create(
                $this->relation_id,
                $prop['id'],
                isset($data[$prop['id']]) ? $data[$prop['id']] : ''
            );
            
            //属性名称别名设置
            if(!empty($labels[$prop['id']])){
                if(!isset($prop['title'])){
                    //若指定$props中未包含title，则去数据库搜索title
                    $prop = PropService::service()->get($prop['id']);
                }
                if($prop['title'] != $labels[$prop['id']]){
                    //若指定title与系统title不一致，则插入别名
                    $this->usage_model->getLabelModel()->insert(array(
                        'relation_id'=>$this->relation_id,
                        'prop_id'=>$prop['id'],
                        'title'=>$labels[$prop['id']],
                    ));
                }
            }
        }
    }
    
    /**
     * 获取一个属性集
     * @param array|null $props 可以指定$props以获取部分属性，若为null，则获取全部属性
     * @return array
     */
    public function getPropSet($props = null){
        if($props === null){
            //根据$relation_id，获取全部相关属性
            $usage_ids = $this->usage_model->getUsages($this->relation_id);
            $props = PropService::service()->getPropsByUsage(
                $usage_ids,
                $this->usage_model->getUsageType(),
                $this->usage_model->getSharedUsages($usage_ids),
                true
            );
        }
        
        //获取自定义labels
        $label_map = ArrayHelper::column($this->usage_model->getLabelModel()->fetchAll(array(
            'relation_id = ?'=>$this->relation_id
        )), 'title', 'prop_id');
        
        $prop_set = array();
        foreach($props as $prop){
            $prop['value'] = $this->getElement($prop['element'])->get($this->relation_id, $prop['id']);
            $prop['title_alias'] = isset($label_map[$prop['id']]) ? $label_map[$prop['id']] : $prop['title'];
            $prop_set[] = $prop;
        }
        return $prop_set;
    }

    /**
     * 更新一个属性集
     * @param array $props 属性集合，没项必须包含id和element字段
     * （不能直接根据$data的键来判断，因为值被置空的时候，可能键不存在）
     * @param array $data 属性值
     * @param array $labels 自定义属性名称
     * @throws ErrorException
     */
    public function updatePropSet(array $props, array $data, $labels = array()){
        foreach($props as $prop){
            if(!isset($prop['id']) || !isset($prop['element'])){
                throw new ErrorException('数据格式异常');
            }
            $this->getElement($prop['element'])->set(
                $this->relation_id,
                $prop['id'],
                isset($data[$prop['id']]) ? $data[$prop['id']] : ''
            );

            //属性名称别名设置
            $old_label = $this->usage_model->getLabelModel()->fetchRow(array(
                'relation_id = ?'=>$this->relation_id,
                'prop_id = ?'=>$prop['id'],
            ));
            if(!empty($labels[$prop['id']])){
                if(!isset($prop['title'])){
                    //若指定$props中未包含title，则去数据库搜索title
                    $prop = PropService::service()->get($prop['id']);
                }
                
                if($old_label){
                    if($prop['title'] == $labels[$prop['id']]){
                        //原先有设置label，但现在改为与系统标题一直，则删除原来的label记录
                        $this->usage_model->getLabelModel()->delete($old_label['id']);
                    }else if($old_label['title'] != $labels[$prop['id']]){
                        //原先有设置label，但是现在变了
                        $this->usage_model->getLabelModel()->update(array(
                            'title'=>$labels[$prop['id']],
                        ), $old_label['id']);
                    }
                }else if($prop['title'] != $labels[$prop['id']]){
                    //原先没有设置label，现在有了
                    $this->usage_model->getLabelModel()->insert(array(
                        'relation_id'=>$this->relation_id,
                        'prop_id'=>$prop['id'],
                        'title'=>$labels[$prop['id']],
                    ));
                }
            }else if($old_label){
                //原先有设置label，现在没了
                $this->usage_model->getLabelModel()->delete($old_label['id']);
            }
        }
    }

    /**
     * 根据属性别名，单一更新一个属性的属性值
     * @param string|int $prop_key 属性ID或别名
     * @param mixed $value 属性值
     * 若属性元素对应的是输入框，文本域或单选框，则直接更新属性值
     * 若属性元素对应的是多选框：
     *  - 当$value是数字的时候，仅做插入（已存在则无操作）操作，
     *  - 当$value是数组的时候，将影响原有的属性值（不存在则删除，已存在则无操作）
     * @return bool
     * @throws ErrorException
     */
    public function setValue($prop_key, $value){
        $prop = PropService::service()->get($prop_key);
        if(!$prop){
            throw new ErrorException("指定属性[{$prop}]不存在");
        }
        
        $this->getElement($prop['element'])->set($this->relation_id, $prop['id'], $value);
        return true;
    }

    /**
     * 获取一个用户属性值
     * @param string|int $prop_key 属性ID或别名
     * @return mixed
     * @throws ErrorException
     */
    public function getValue($prop_key){
        $prop = PropService::service()->get($prop_key);
        if(!$prop){
            throw new ErrorException("指定属性[{$prop}]不存在");
        }
        
        return $this->getElement($prop['element'])->get($this->relation_id, $prop['id']);
    }

    /**
     * 获取表单元素实例
     * @param int $element 表单元素（以数字的形式存在表里）
     * @return ElementAbstract
     */
    protected function getElement($element){
        if(!isset($this->elementInstances[$element])){
            $this->elementInstances[$element] = new self::$elementMap[$element]($this->usage_model);
        }
        
        return $this->elementInstances[$element];
    }
}