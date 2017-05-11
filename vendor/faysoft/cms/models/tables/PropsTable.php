<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * Props table model
 *
 * @property int $id Id
 * @property int $type 关联类型
 * @property string $title 属性名称
 * @property int $element 表单元素
 * @property int $required 必选标记
 * @property string $alias 别名
 * @property int $delete_time 删除时间
 * @property int $create_time 创建时间
 * @property int $is_show 是否默认显示
 */
class PropsTable extends Table{
    /**
     * 录入方式-文本框
     */
    const ELEMENT_TEXT = 1;

    /**
     * 录入方式-单选框
     */
    const ELEMENT_RADIO = 2;

    /**
     * 录入方式-下拉框
     */
    const ELEMENT_SELECT = 3;

    /**
     * 录入方式-多选框
     */
    const ELEMENT_CHECKBOX = 4;

    /**
     * 录入方式-文本域
     */
    const ELEMENT_TEXTAREA = 5;

    /**
     * 录入方式-纯数字输入框
     */
    const ELEMENT_NUMBER = 6;

    /**
     * 录入方式-图片
     */
    const ELEMENT_IMAGE = 7;

    /**
     * 录入方式-文件
     */
    const ELEMENT_FILE = 8;

    /**
     * 类型-文章分类属性
     */
    const TYPE_POST_CAT = 1;

    /**
     * 类型-角色附加属性
     */
    const TYPE_ROLE = 2;
    
    protected $_name = 'props';

    public static $type_map = array(
        self::TYPE_POST_CAT => '文章',
        self::TYPE_ROLE => '角色',
    );
    
    public static $element_map = array(
        self::ELEMENT_TEXT => '文本框',
        self::ELEMENT_RADIO => '单选框',
        self::ELEMENT_SELECT => '下拉框',
        self::ELEMENT_CHECKBOX => '多选框',
        self::ELEMENT_TEXTAREA => '文本域',
        self::ELEMENT_NUMBER => '数字输入框',
        self::ELEMENT_IMAGE => '图片',
        self::ELEMENT_FILE => '文件',
    );

    /**
     * @param string $class_name
     * @return PropsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }

    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('type', 'element'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>255)),
            array(array('alias'), 'string', array('max'=>50)),
            array(array('is_show', 'required'), 'range', array('range'=>array(0, 1))),
            
            array('title', 'required'),
            array('alias', 'unique', array('table'=>'props', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('cms/admin/prop/is-alias-not-exist'))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'type'=>'关联类型',
            'title'=>'属性名称',
            'element'=>'表单元素',
            'required'=>'必选标记',
            'alias'=>'别名',
            'delete_time'=>'删除时间',
            'create_time'=>'创建时间',
            'is_show'=>'是否默认显示',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'type'=>'intval',
            'title'=>'trim',
            'element'=>'intval',
            'required'=>'intval',
            'alias'=>'trim',
            'is_show'=>'intval',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
                return array(
                    'id', 'create_time', 'delete_time', 'type'//用途不允许修改，改掉的话老数据就很难处理了
                );
                break;
            default:
                return array();
        }
    }
}