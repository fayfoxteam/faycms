<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg domain table model
 * 
 * @property int $id Id
 * @property int $website_id 网站ID
 * @property string $domain Domain
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property int $is_system 1：系统内置的域名
 * @property string $deleted_at 删除时间
 */
class GgDomainTable extends Table{
    protected $_name = 'gg_domain';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('domain'), 'string', array('max'=>255)),
            array(array('is_system'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'website_id'=>'网站ID',
            'domain'=>'Domain',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'is_system'=>'1：系统内置的域名',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'domain'=>'trim',
            'updated_at'=>'',
            'created_at'=>'',
            'is_system'=>'intval',
            'deleted_at'=>'',
        );
    }
}