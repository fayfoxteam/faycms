<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 任务
 *
 * @property int $id Id
 * @property string $name 名称
 * @property int $enabled 是否启用
 */
class GuangongTasksTable extends Table{
    protected $_name = 'guangong_tasks';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('name'), 'string', array('max'=>255)),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'名称',
            'enabled'=>'是否启用',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'enabled'=>'intval',
        );
    }
}