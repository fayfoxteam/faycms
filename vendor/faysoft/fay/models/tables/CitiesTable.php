<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Cities table model
 *
 * @property int $id Id
 * @property string $city City
 * @property int $parent Parent
 * @property string $spelling Spelling
 * @property string $abbr 缩写
 * @property string $short 单个首字母
 */
class CitiesTable extends Table{
    protected $_name = 'cities';
    
    /**
     * @param string $class_name
     * @return CitiesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('parent'), 'int', array('min'=>-32768, 'max'=>32767)),
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('city'), 'string', array('max'=>255)),
            array(array('spelling'), 'string', array('max'=>50)),
            array(array('abbr', 'short'), 'string', array('max'=>30)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'city'=>'City',
            'parent'=>'Parent',
            'spelling'=>'Spelling',
            'abbr'=>'缩写',
            'short'=>'单个首字母',
        );
    }

    public function filters(){
        return array(
            'city'=>'trim',
            'parent'=>'intval',
            'spelling'=>'trim',
            'abbr'=>'trim',
            'short'=>'trim',
        );
    }
}