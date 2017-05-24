<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 小工具域
 *
 * @property int $id Id
 * @property string $alias 别名
 * @property string $description 描述
 * @property int $sort 排序值
 */
class WidgetAreasTable extends Table{
    protected $_name = 'widget_areas';

    /**
     * @param string $class_name
     * @return WidgetAreasTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }

    public function rules(){
        return array(
            array(array('id', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('alias'), 'string', array('max'=>50)),
            array(array('description'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'alias'=>'别名',
            'description'=>'描述',
            'sort'=>'排序值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'alias'=>'trim',
            'description'=>'trim',
            'sort'=>'intval',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
                return array(
                    'id'
                );
                break;
            default:
                return array();
        }
    }
}