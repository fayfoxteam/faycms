<?php
namespace baike\models\tables;

use fay\core\db\Table;
use fay\core\Loader;
use fay\core\Validator;

/**
 * 域名后缀
 * 
 * @property int $id Id
 * @property string $suffix 域名后缀（带点号）
 * @property string $description 描述
 * @property int $sort 排序
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class BaikeDomainSuffixesTable extends Table{
    protected $_name = 'baike_domain_suffixes';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        Validator::registerValidator('domain_suffix', 'baike\validators\DomainSuffixValidator');
        
        return array(
            array(array('id', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('suffix'), 'string', array('max'=>30)),
            array(array('description'), 'string', array('max'=>500)),
            
            array('suffix', 'required'),
            array('suffix', 'domain_suffix'),
            array('suffix', 'unique', array('table'=>$this->getTableName(), 'except'=>'id', 'ajax'=>array('baike/admin/domain-suffix/is-suffix-not-exist')))
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'suffix'=>'域名后缀',
            'description'=>'描述',
            'sort'=>'排序',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'suffix'=>'trim',
            'description'=>'trim',
            'sort'=>'intval',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
            default:
                return array(
                    'id', 'create_time', 'sort'
                );
        }
    }
}