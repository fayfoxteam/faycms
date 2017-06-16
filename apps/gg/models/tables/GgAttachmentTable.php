<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 附件表
 * 
 * @property int $id Id
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property int $website_id 网站ID
 * @property int $cat_id Cat Id
 * @property string $title 原文件名
 * @property string $filepath Filepath
 * @property int $filetype Filetype
 * @property int $filesize Filesize
 * @property int $haslitpic Haslitpic
 * @property int $uploadtime Uploadtime
 * @property int $aid Aid
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at Deleted At
 */
class GgAttachmentTable extends Table{
    protected $_name = 'gg_attachment';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'aid'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('merchant_id', 'website_id', 'cat_id', 'filesize'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('haslitpic'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('filetype'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>60)),
            array(array('filepath'), 'string', array('max'=>200)),
            array(array('uploadtime'), 'datetime'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'website_id'=>'网站ID',
            'cat_id'=>'Cat Id',
            'title'=>'原文件名',
            'filepath'=>'Filepath',
            'filetype'=>'Filetype',
            'filesize'=>'Filesize',
            'haslitpic'=>'Haslitpic',
            'uploadtime'=>'Uploadtime',
            'aid'=>'Aid',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'merchant_id'=>'intval',
            'website_id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'filepath'=>'trim',
            'filetype'=>'intval',
            'filesize'=>'intval',
            'haslitpic'=>'intval',
            'uploadtime'=>'intval',
            'aid'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}