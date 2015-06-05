<?php 
namespace ncp\helpers;

class FriendlyLink{
	/**
	 * 返回产品链接
	 * @param array $params 必须传入ID
	 */
	public static function getProductLink($params){
		return \F::app()->view->url('product/'.$params['id']);
	}
	
	/**
	 * 返回产品列表URL
	 * @param array $params
	 */
	public static function getProductListLink($params){
		return \F::app()->view->url('product/'.(isset($params['area']) ? $params['area'] : 0).
			'-'.(isset($params['cat']) ? $params['cat'] : 0).
			'-'.(isset($params['month']) ? $params['month'] : 0).
			'-'.(isset($params['page']) ? $params['page'] : 1));
	}
	
	/**
	 * 返回食品链接
	 * @param array $params 必须传入ID
	 */
	public static function getFoodLink($params){
		return \F::app()->view->url('food/'.$params['id']);
	}
	
	/**
	 * 返回食品列表链接
	 * @param array $params 必须传入ID
	 */
	public static function getFoodListLink($params){
		return \F::app()->view->url('food/'.(isset($params['area']) ? $params['area'] : 0).
			'-'.(isset($params['cat']) ? $params['cat'] : 0).
			'-'.(isset($params['month']) ? $params['month'] : 0).
			'-'.(isset($params['page']) ? $params['page'] : 1));
	}
	
	/**
	 * 返回旅游链接
	 * @param array $params 必须传入ID
	 */
	public static function getTravelLink($params){
		return \F::app()->view->url('travel/'.$params['id']);
	}
	
	/**
	 * 返回旅游列表URL
	 * @param array $params
	 */
	public static function getTravelListLink($params){
		return \F::app()->view->url('travel/'.(isset($params['area']) ? $params['area'] : 0).
			'-'.(isset($params['cat']) ? $params['cat'] : 0).
			'-'.(isset($params['month']) ? $params['month'] : 0).
			'-'.(isset($params['page']) ? $params['page'] : 1));
	}
	
	/**
	 * 返回专题URL
	 * @param array $params
	 */
	public static function getSpecialLink($params){
		return \F::app()->view->url('special/'.$params['id']);
	}
	
	/**
	 * 返回专题列表URL
	 * @param array $params
	 */
	public static function getSpecialListLink($params){
		return \F::app()->view->url('special/?page='.(isset($params['page']) ? $params['page'] : 1));
	}
	
	/**
	 * 返回资讯URL
	 * @param array $params
	 */
	public static function getNewsLink($params){
		return \F::app()->view->url('news/'.$params['id']);
	}
	
	/**
	 * 返回资讯列表URL
	 * @param array $params
	 */
	public static function getNewsListLink($params){
		return \F::app()->view->url('news/?page='.(isset($params['page']) ? $params['page'] : 1));
	}
	
	public static function getLink($type, $params){
		switch($type){
			case 'product_list':
				return self::getProductListLink($params);
			break;
			case 'travel_list':
				return self::getTravelListLink($params);
			break;
			case 'special_list':
				return self::getSpecialListLink($params);
			break;
			case 'news_list':
				return self::getNewsListLink($params);
			break;
		}
	}
}