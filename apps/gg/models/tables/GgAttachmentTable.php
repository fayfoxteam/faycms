<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 附件表
 *
 * @property int $id Id
 * @property int $website_id 网站ID
 * @property int $cat_id Cat Id
 * @property string $title 原文件名
 * @property string $filepath Filepath
 * @property int $filetype Filetype
 * @property int $filesize Filesize
 * @property int $is_image 是否为图片
 * @property int $image_width 图片宽度
 * @property int $image_height 图片高度
 * @property string $mime_type Mime Type
 * @property int $qiniu 是否上传至七牛
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
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id', 'cat_id', 'filesize', 'image_width', 'image_height'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('qiniu'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('filetype'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>60)),
            array(array('filepath'), 'string', array('max'=>200)),
            array(array('mime_type'), 'string', array('max'=>255)),
            array(array('is_image'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'website_id'=>'网站ID',
            'cat_id'=>'Cat Id',
            'title'=>'原文件名',
            'filepath'=>'Filepath',
            'filetype'=>'Filetype',
            'filesize'=>'Filesize',
            'is_image'=>'是否为图片',
            'image_width'=>'图片宽度',
            'image_height'=>'图片高度',
            'mime_type'=>'Mime Type',
            'qiniu'=>'是否上传至七牛',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'website_id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'filepath'=>'trim',
            'filetype'=>'intval',
            'filesize'=>'intval',
            'is_image'=>'intval',
            'image_width'=>'intval',
            'image_height'=>'intval',
            'mime_type'=>'trim',
            'qiniu'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}