<?php
namespace fayshop\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Shop goods cat prop values table model
 * 
 * @property int $id Id
 * @property int $cat_id 分类ID
 * @property int $prop_id 属性ID
 * @property string $title 标题
 * @property int $delete_time 删除时间
 * @property int $sort 排序值i
 */
class ShopGoodsCatPropValuesTable extends Table{
    protected $_name = 'shop_goods_cat_prop_values';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_id'=>'分类ID',
            'prop_id'=>'属性ID',
            'title'=>'标题',
            'delete_time'=>'删除时间',
            'sort'=>'排序值i',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_id'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
            'sort'=>'intval',
        );
    }
}