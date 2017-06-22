<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg templates table model
 *
 * @property int $id Id
 * @property int $cat_bid Cat Bid
 * @property int $cat_sid Cat Sid
 * @property int $website_id 网站ID
 * @property string $title Title
 * @property string $description Description
 * @property int $thumbnail 缩略图
 * @property string $author Author
 * @property int $position Position
 * @property string $url Url
 * @property int $updated_ip Updated Ip
 * @property string $updated_at Updated At
 * @property int $created_ip Created Ip
 * @property string $created_at Created At
 * @property string $deleted_at Deleted At
 * @property int $mobile_thumbnail Mobile Thumbnail
 * @property int $status 审核状态 0 待审核 1 审核通过 2 审核不通过
 */
class GgTemplatesTable extends Table{
    protected $_name = 'gg_templates';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('updated_ip', 'created_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('thumbnail', 'mobile_thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_bid', 'cat_sid', 'website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('position'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('title', 'author'), 'string', array('max'=>32)),
            array(array('description', 'url'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_bid'=>'Cat Bid',
            'cat_sid'=>'Cat Sid',
            'website_id'=>'网站ID',
            'title'=>'Title',
            'description'=>'Description',
            'thumbnail'=>'缩略图',
            'author'=>'Author',
            'position'=>'Position',
            'url'=>'Url',
            'updated_ip'=>'Updated Ip',
            'updated_at'=>'Updated At',
            'created_ip'=>'Created Ip',
            'created_at'=>'Created At',
            'deleted_at'=>'Deleted At',
            'mobile_thumbnail'=>'Mobile Thumbnail',
            'status'=>'审核状态 0 待审核 1 审核通过 2 审核不通过',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_bid'=>'intval',
            'cat_sid'=>'intval',
            'website_id'=>'intval',
            'title'=>'trim',
            'description'=>'trim',
            'thumbnail'=>'intval',
            'author'=>'trim',
            'position'=>'intval',
            'url'=>'trim',
            'updated_ip'=>'intval',
            'updated_at'=>'',
            'created_ip'=>'intval',
            'created_at'=>'',
            'deleted_at'=>'',
            'mobile_thumbnail'=>'intval',
            'status'=>'intval',
        );
    }
}