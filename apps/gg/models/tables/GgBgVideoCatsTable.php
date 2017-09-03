<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 背景视频分类
 * 
 * @property int $id Id
 * @property string $name 分类名称
 * @property int $parent_id 父分类
 * @property int $sort 排序
 * @property int $left_value Left Value
 * @property int $right_value Right Value
 * @property string $created_at Created At
 * @property string $updated_at Updated At
 * @property string $deleted_at Deleted At
 */
class GgBgVideoCatsTable extends Table{
    protected $_name = 'gg_bg_video_cats';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('id', 'parent_id', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>30)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'分类名称',
            'parent_id'=>'父分类',
            'sort'=>'排序',
            'left_value'=>'Left Value',
            'right_value'=>'Right Value',
            'created_at'=>'Created At',
            'updated_at'=>'Updated At',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'parent_id'=>'intval',
            'sort'=>'intval',
            'created_at'=>'',
            'updated_at'=>'',
            'deleted_at'=>'',
        );
    }
}