<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Links table model
 *
 * @property int $id Id
 * @property string $title 标题
 * @property string $description 描述
 * @property string $url 网址
 * @property int $visible 可见
 * @property int $user_id 添加者
 * @property string $target 打开方式
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $sort 排序值
 * @property int $logo Logo
 * @property int $cat_id 分类
 */
class LinksTable extends Table{
    protected $_name = 'links';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('user_id', 'logo'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('visible'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title', 'description', 'url'), 'string', array('max'=>255)),
            array(array('target'), 'string', array('max'=>25)),
            
            array('url', 'url'),
            array(array('title', 'url'), 'required')
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'title'=>'标题',
            'description'=>'描述',
            'url'=>'网址',
            'visible'=>'可见',
            'user_id'=>'用户ID',
            'target'=>'打开方式',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'sort'=>'排序值',
            'logo'=>'Logo',
            'cat_id'=>'分类',
        );
    }

    public function filters(){
        return array(
            'title'=>'trim',
            'description'=>'trim',
            'url'=>'trim',
            'visible'=>'intval',
            'user_id'=>'intval',
            'target'=>'trim',
            'sort'=>'intval',
            'logo'=>'intval',
            'cat_id'=>'intval',
        );
    }
}