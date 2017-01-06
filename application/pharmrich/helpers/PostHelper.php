<?php 
namespace pharmrich\helpers;


use fay\services\CategoryService;
class PostHelper{
	/**
	 * 获取根据文章分类ID，获取文章类型（商品/食谱/新闻）
	 * @param int $cat_id 分类ID
	 */
	public static function getType($cat_id){
		$cat = CategoryService::service()->get($cat_id, 'left_value,right_value');
		if(CategoryService::service()->isChild($cat, 'products')){
			return 'product';
		}else if(CategoryService::service()->isChild($cat, 'cook-recipes')){
			return 'cook-recipe';
		}else{
			return 'news';
		}
		
	}
}