<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 背景视频
 *
 * @property int $id Id
 * @property string $title 标题
 * @property int $file_id 视频文件ID
 * @property int $thumbnail 缩略图文件ID
 * @property int $cat_id 分类ID
 * @property string $created_at Created At
 * @property string $deleted_at Deleted At
 */
class GgBgVideosTable extends Table{
    protected $_name = 'gg_bg_videos';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('file_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('title'), 'string', array('max'=>100)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'title'=>'标题',
            'file_id'=>'视频文件ID',
            'thumbnail'=>'缩略图文件ID',
            'cat_id'=>'分类ID',
            'created_at'=>'Created At',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'title'=>'trim',
            'file_id'=>'intval',
            'thumbnail'=>'intval',
            'cat_id'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}