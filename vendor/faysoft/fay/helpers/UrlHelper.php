<?php
namespace fay\helpers;

class UrlHelper{
	public static function createUrl($router = null, $params = array()){
		$base_url = \F::config()->get('base_url');
		if(!$router){
			return $base_url;
		}else{
			$default_module = \F::config()->get('default_router.module');
			if(strpos($router, $default_module . '/') === 0){
				$router = substr($router, strlen($default_module) + 1);
			}
			$ext = \F::config()->get('url_suffix');
			$exts = \F::config()->get('*', 'exts');
			foreach($exts as $key => $val){
				foreach($val as $v){
					if(preg_match('/^'.str_replace(array(
							'/', '*',
						), array(
							'\/', '.*',
						), $v).'$/i', $router)){
						$ext = $key;
						break 2;
					}
				}
			}
			
			if($params){
				return $base_url . $router . $ext . '?' . http_build_query($params);
			}else{
				return $base_url . $router . $ext;
			}
		}
	}
	
	/**
	 * 返回public/apps/{APPLICATION}下的文件路径
	 * 用于返回自定义application的静态文件
	 * @param string $uri
	 * @return string
	 */
	public static function appAssets($uri){
		return \F::config()->get('app_assets_url') . $uri;
	}
	
	/**
	 * 返回public/assets/下的文件路径（第三方jquery类库等）
	 * 主要是考虑到以后如果要做静态资源分离，只要改这个函数就好了
	 * @param string $uri
	 * @return string
	 */
	public static function assets($uri){
		return \F::config()->get('assets_url') . $uri;
	}
}