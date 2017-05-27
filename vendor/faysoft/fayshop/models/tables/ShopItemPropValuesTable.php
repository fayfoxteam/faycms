<?php
namespace fayshop\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Shop item prop values table model
 * 
 * @property int $id Id
 * @property int $cat_id Cat Id
 * @property int $prop_id Prop Id
 * @property string $title Title
 * @property string $title_alias Title Alias
 * @property int $is_terminal Is Terminal
 * @property int $delete_time 删除时间
 * @property int $sort Sort
 */
class ShopItemPropValuesTable extends Table{
    protected $_name = 'shop_item_prop_values';
    
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
            array(array('title', 'title_alias'), 'string', array('max'=>255)),
            array(array('is_terminal'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'cat_id'=>'Cat Id',
            'prop_id'=>'Prop Id',
            'title'=>'Title',
            'title_alias'=>'Title Alias',
            'is_terminal'=>'Is Terminal',
            'delete_time'=>'删除时间',
            'sort'=>'Sort',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'cat_id'=>'intval',
            'prop_id'=>'intval',
            'title'=>'trim',
            'title_alias'=>'trim',
            'is_terminal'=>'intval',
            'sort'=>'intval',
        );
    }
}