<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Widgets table model
 *
 * @property int $id Id
 * @property string $alias 别名
 * @property string $config 配置参数
 * @property string $widget_name 小工具名称
 * @property string $description 小工具描述
 * @property int $enabled 是否启用
 * @property int $ajax 是否ajax引入
 * @property int $cache 是否缓存
 */
class WidgetsTable extends Table{
    protected $_name = 'widgets';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('cache'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('enabled', 'ajax'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('alias'), 'string', array('max'=>50)),
            array(array('widget_name', 'description'), 'string', array('max'=>255)),

            array('alias', 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'alias'=>'别名',
            'config'=>'实例参数',
            'widget_name'=>'小工具名称',
            'description'=>'小工具描述',
            'enabled'=>'是否启用',
            'ajax'=>'是否ajax引入',
            'cache'=>'是否缓存',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'alias'=>'trim',
            'config'=>'',
            'widget_name'=>'trim',
            'description'=>'trim',
            'enabled'=>'intval',
            'ajax'=>'intval',
            'cache'=>'intval',
        );
    }
}