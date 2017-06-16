<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 模块下的列表
 * 
 * @property int $id 自增ID
 * @property int $page_id 所属页面的id
 * @property int $design_id 关联的模块id
 * @property int $website_id 网站ID
 * @property int $is_enable 是否启用:1启用,0关闭
 * @property int $sort 排序
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgDesignInfoTable extends Table{
    protected $_name = 'gg_design_info';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'page_id', 'design_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('is_enable'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'page_id'=>'所属页面的id',
            'design_id'=>'关联的模块id',
            'website_id'=>'网站ID',
            'is_enable'=>'是否启用:1启用,0关闭',
            'sort'=>'排序',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'page_id'=>'intval',
            'design_id'=>'intval',
            'website_id'=>'intval',
            'is_enable'=>'intval',
            'sort'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}