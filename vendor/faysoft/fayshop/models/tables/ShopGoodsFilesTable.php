<?php
namespace fayshop\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Shop goods files table model
 * 
 * @property int $goods_id 商品Id
 * @property int $file_id 文件Id
 * @property string $description 描述
 * @property int $sort 排序值
 * @property int $create_time 创建时间
 */
class ShopGoodsFilesTable extends Table{
    protected $_name = 'shop_goods_files';
    protected $_primary = array('goods_id', 'file_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('goods_id', 'file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('description'), 'string', array('max'=>255)),
        );
    }

    public function labels(){
        return array(
            'goods_id'=>'商品Id',
            'file_id'=>'文件Id',
            'description'=>'描述',
            'sort'=>'排序值',
            'create_time'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'goods_id'=>'intval',
            'file_id'=>'intval',
            'description'=>'trim',
            'sort'=>'intval',
        );
    }
}