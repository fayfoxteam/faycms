<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 网站的短消息表
 *
 * @property int $id Id
 * @property int $website_id 网站ID
 * @property string $title Title
 * @property string $content Content
 * @property int $is_read 1为已读
 * @property int $read_ip Read Ip
 * @property string $read_at Read At
 * @property int $updated_ip Updated Ip
 * @property string $updated_at Updated At
 * @property int $created_ip Created Ip
 * @property string $created_at Created At
 * @property string $deleted_at Deleted At
 */
class GgMessageTable extends Table{
    protected $_name = 'gg_message';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('read_ip', 'updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('title'), 'string', array('max'=>32)),
            array(array('content'), 'string', array('max'=>255)),
            array(array('is_read'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'website_id'=>'网站ID',
            'title'=>'Title',
            'content'=>'Content',
            'is_read'=>'1为已读',
            'read_ip'=>'Read Ip',
            'read_at'=>'Read At',
            'updated_ip'=>'Updated Ip',
            'updated_at'=>'Updated At',
            'created_ip'=>'Created Ip',
            'created_at'=>'Created At',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'title'=>'trim',
            'content'=>'trim',
            'is_read'=>'intval',
            'read_ip'=>'intval',
            'read_at'=>'',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}