<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * 小工具域-小工具关联关系
 *
 * @property int $id Id
 * @property int $widget_area_id 小工具域ID
 * @property int $widget_id 小工具ID
 * @property int $sort 排序值
 */
class WidgetAreasWidgetsTable extends Table{
    protected $_name = 'widget_areas_widgets';

    /**
     * @param string $class_name
     * @return WidgetAreasWidgetsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }

    public function rules(){
        return array(
            array(array('id', 'widget_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('widget_area_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'widget_area_id'=>'小工具域ID',
            'widget_id'=>'小工具ID',
            'sort'=>'排序值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'widget_area_id'=>'intval',
            'widget_id'=>'intval',
            'sort'=>'intval',
        );
    }
}