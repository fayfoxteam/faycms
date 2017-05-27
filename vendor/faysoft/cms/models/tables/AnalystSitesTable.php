<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Analyst Sites table model
 *
 * @property int $id Id
 * @property string $title 站点名称
 * @property string $description 描述
 * @property int $delete_time 删除时间
 */
class AnalystSitesTable extends Table{
    protected $_name = 'analyst_sites';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('title', 'description'), 'string', array('max'=>255)),
            
            array('title', 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'title'=>'站点名称',
            'description'=>'描述',
            'delete_time'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'title'=>'trim',
            'description'=>'trim',
        );
    }
}