<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 防区表
 *
 * @property int $id 防区ID
 * @property string $name 防区名称
 * @property int $picture 图片
 * @property int $text_picture 描述
 * @property int $sort 排序值
 * @property int $enabled 是否启用
 */
class GuangongDefenceAreasTable extends Table{
    protected $_name = 'guangong_defence_areas';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('picture', 'text_picture'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'防区ID',
            'name'=>'防区名称',
            'picture'=>'图片',
            'text_picture'=>'Text-picture',
            'sort'=>'排序值',
            'enabled'=>'是否启用',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'picture'=>'intval',
            'text_picture'=>'intval',
            'sort'=>'intval',
            'enabled'=>'intval',
        );
    }
}