<?php 
namespace pharmrich\helpers;


use fay\models\Category;
class PostHelper{
	/**
	 * 获取根据文章分类ID，获取文章类型（商品/食谱/新闻）
	 * @param int $cat_id 分类ID
	 */
	public static function getType($cat_id){
		$cat = Category::model()->get($cat_id, 'left_value,right_value');
		if(Category::model()->isChild($cat, 'products')){
			return 'products';
		}else if(Category::model()->isChild($cat, 'cook-recipes')){
			return 'cook-recipes';
		}else{
			return 'news';
		}
		
	}
}