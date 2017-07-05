<?php
namespace apidoc\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * API分类表
 *
 * @property int $id Id
 * @property int $app_id Api ID
 * @property string $title 标题
 * @property string $alias 别名
 * @property int $parent 父节点
 * @property int $sort 排序值
 * @property string $description 描述
 * @property int $is_nav 是否导航栏显示
 * @property int $count 记录数
 * @property int $left_value Left Value
 * @property int $right_value Right Value
 * @property int $is_system Is System
 */
class ApidocApiCategoriesTable extends Table{
    protected $_name = 'apidoc_api_categories';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('id', 'parent', 'count', 'left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('app_id'), 'int', array('min'=>-32768, 'max'=>32767)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('title'), 'string', array('max'=>255)),
            array(array('alias'), 'string', array('max'=>50)),
            array(array('description'), 'string', array('max'=>500)),
            array(array('is_nav', 'is_system'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'app_id'=>'Api ID',
            'title'=>'标题',
            'alias'=>'别名',
            'parent'=>'父节点',
            'sort'=>'排序值',
            'description'=>'描述',
            'is_nav'=>'是否导航栏显示',
            'count'=>'记录数',
            'left_value'=>'Left Value',
            'right_value'=>'Right Value',
            'is_system'=>'Is System',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'app_id'=>'intval',
            'title'=>'trim',
            'alias'=>'trim',
            'parent'=>'intval',
            'sort'=>'intval',
            'description'=>'trim',
            'is_nav'=>'intval',
            'count'=>'intval',
            'is_system'=>'intval',
        );
    }
}