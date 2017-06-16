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
 * @property int $website_id 所属网站id
 * @property int $limit 每页数量
 * @property string $column 排序字段
 * @property int $direction 顺序:1asc,2desc
 * @property int $offset 从第几页开始
 * @property string $data json数据
 * @property int $is_enable 是否启用
 * @property int $sorting 排序
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgDesignListTable extends Table{
    protected $_name = 'gg_design_list';

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
            array(array('direction'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('limit', 'offset', 'sorting'), 'int', array('min'=>0, 'max'=>255)),
            array(array('column'), 'string', array('max'=>50)),
            array(array('is_enable'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'自增ID',
            'page_id'=>'所属页面的id',
            'design_id'=>'关联的模块id',
            'website_id'=>'所属网站id',
            'limit'=>'每页数量',
            'column'=>'排序字段',
            'direction'=>'顺序:1asc,2desc',
            'offset'=>'从第几页开始',
            'data'=>'json数据',
            'is_enable'=>'是否启用',
            'sorting'=>'排序',
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
            'limit'=>'intval',
            'column'=>'trim',
            'direction'=>'intval',
            'offset'=>'intval',
            'data'=>'',
            'is_enable'=>'intval',
            'sorting'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}