<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 兵种表
 *
 * @property int $id Id
 * @property string $name 名称
 * @property int $picture Picture
 * @property int $sort 排序值
 * @property int $enabled 是否启用
 * @property int $description_picture 描述图片
 */
class GuangongArmsTable extends Table{
    protected $_name = 'guangong_arms';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('picture', 'description_picture'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('id', 'sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name'), 'string', array('max'=>30)),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'名称',
            'picture'=>'Picture',
            'sort'=>'排序值',
            'enabled'=>'是否启用',
            'description_picture'=>'描述图片',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'picture'=>'intval',
            'sort'=>'intval',
            'enabled'=>'intval',
            'description_picture'=>'intval',
        );
    }
}