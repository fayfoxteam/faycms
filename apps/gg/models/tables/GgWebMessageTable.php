<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 网站留言表
 * 
 * @property int $id Id
 * @property int $website_id 网站ID
 * @property string $name Name
 * @property string $data json数据
 * @property int $ip Ip
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgWebMessageTable extends Table{
    protected $_name = 'gg_web_message';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('name'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'website_id'=>'网站ID',
            'name'=>'Name',
            'data'=>'json数据',
            'ip'=>'Ip',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'name'=>'trim',
            'data'=>'',
            'ip'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}