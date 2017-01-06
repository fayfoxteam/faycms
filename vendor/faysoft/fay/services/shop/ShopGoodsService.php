<?php
namespace fay\services\shop;

use fay\core\Service;
use fay\models\tables\Goods as TableGoods;
use fay\models\tables\GoodsFiles;
use fay\core\Sql;
use fay\models\tables\GoodsSkus;
use fay\models\tables\GoodsCatProps;

class ShopGoodsService extends Service{
	/**
	 * @return ShopGoodsService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	public function get($id, $fields = 'files,props,sku'){
		$fields = explode(',', $fields);
		$goods = TableGoods::model()->find($id);
		
		if(!$goods){
			return array();
		}
		
		if(in_array('files', $fields)){
			//画廊
			$goods['files'] = GoodsFiles::model()->fetchAll(array(
				'goods_id = ?'=>$id,
			), '*', 'sort');
		}
		
		if(in_array('props', $fields)){
			//属性
			$goods['props'] = array();
			$sql = new Sql();
			$goods_props = $sql->from(array('gpv'=>'goods_prop_values'))
				->joinLeft(array('cp'=>'goods_cat_props'), 'gpv.prop_id = cp.id', GoodsCatProps::model()->formatFields('!id'))
				->where(array(
					'gpv.goods_id = ?'=>$id,
					'cp.deleted = 0',
				))
				->order('cp.sort')
				->order('gpv.prop_id')
				->order('gpv.prop_value_id')
				->fetchAll()
			;
			$last_prop_id = 0;
			foreach($goods_props as $gp){
				if($gp['prop_id'] != $last_prop_id){
					$last_prop_id = $gp['prop_id'];
					$goods['props'][$last_prop_id] = array(
						'prop_id'=>$gp['prop_id'],
						'type'=>$gp['type'],
						'required'=>$gp['required'],
						'title'=>$gp['title'],
						'is_sale_prop'=>$gp['is_sale_prop'],
						'is_input_prop'=>$gp['is_input_prop'],
						'deleted'=>$gp['deleted'],
						'sort'=>$gp['sort'],
						'multi'=>$gp['type'] == GoodsCatProps::TYPE_CHECK ? true : false,
						'values'=>array(
							$gp['prop_value_id'] => $gp['prop_value_alias'],
						),
					);
				}else{
					$goods['props'][$last_prop_id]['values'][$gp['prop_value_id']] = $gp['prop_value_alias'];
				}
			}
			
		}
		
		if(in_array('sku', $fields)){
			//sku
			$skus = GoodsSkus::model()->fetchAll(array(
				'goods_id = ?'=>$id,
			), '!goods_id');
			foreach($skus as $s){
				$goods['skus'][$s['sku_key']] = $s;
			}
		}

		return $goods;
	}
}