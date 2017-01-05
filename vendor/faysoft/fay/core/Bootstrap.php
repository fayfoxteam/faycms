<?php
namespace fay\core;

use fay\helpers\Runtime;

class Bootstrap{
	public function init(){
		//默认时区
		date_default_timezone_set(\F::config()->get('date.default_timezone'));
		
		//报错级别
		switch (\F::config()->get('environment')){
			case 'development':
				error_reporting(E_ALL);
				break;
			case 'production':
				error_reporting(0);
				break;
		}
		
		//路由
		$uri = new Uri();
		Runtime::append(__FILE__, __LINE__, '路由解析完成');
		
		//触发事件
		\F::event()->trigger('after_uri');
		
		if(!$uri->router){
			//路由解析失败
			throw new HttpException('Routing format illegal');
		}
		
		//根据router来读取缓存
		if(!\F::input()->get('__r')){//强制跳过缓存，主要用于测试
			$cache_routers = \F::config()->get('*', 'pagecache');
			$cache_routers_keys = array_keys($cache_routers);
			if(!Input::getInstance()->post() && in_array($uri->router, $cache_routers_keys)){
				$filename = md5(json_encode(Input::getInstance()->get(isset($cache_routers[$uri->router]['params']) ? $cache_routers[$uri->router]['params'] : array())));
				$cache_key = 'pages/' . $uri->router . '/' . $filename;
				$content = \F::cache()->get($cache_key);
				if($content){
					if(\F::config()->get('debug')){
						echo '来自缓存';
					}
					echo $content;
					die;
				}
			}
		}
		
		$file = $this->getControllerAndAction($uri);
		Runtime::append(__FILE__, __LINE__, '获取控制器名称');
		
		$controller = new $file['controller'];
		Runtime::append(__FILE__, __LINE__, '控制器被实例化');
		//触发事件
		\F::event()->trigger('after_controller_constructor');
		$controller->{$file['action']}();
		Runtime::append(__FILE__, __LINE__, '控制器方式执行完毕');
	}
	
	/**
	 * 查找对应的controller文件和action方法
	 * @param Uri $uri
	 * @return array
	 * @throws HttpException
	 */
	private function getControllerAndAction($uri){
		//先找当前app目录
		if(file_exists(MODULE_PATH . $uri->module  . '/controllers/' . ucfirst($uri->controller) . 'Controller.php')){
			$class_name = '\\'.APPLICATION.'\modules\\'.$uri->module.'\controllers\\'.$uri->controller.'Controller';
			if(method_exists($class_name, $uri->action)){
				//直接对应的action
				return array(
					'controller'=>$class_name,
					'action'=>$uri->action,
				);
			}else if(method_exists($class_name, $uri->action.'Action')){
				//特殊关键词可能需要添加Action后缀
				return array(
					'controller'=>$class_name,
					'action'=>$uri->action.'Action',
				);
			}else{
				throw new HttpException("Action \"{$uri->action}\" Not Found IN Controller \"{$class_name}\"");
			}
		}
		
		//无直接对应的类文件或者类文件中无此Action，查找后台
		if(file_exists(BACKEND_PATH . 'modules/'. $uri->module . '/controllers/'. ucfirst($uri->controller) . 'Controller.php')){
			$class_name = '\cms\modules\\'.$uri->module.'\controllers\\'.$uri->controller.'Controller';
			if(method_exists($class_name, $uri->action)){
				//直接对应的action
				return array(
					'controller'=>$class_name,
					'action'=>$uri->action,
				);
			}else if(method_exists($class_name, $uri->action.'Action')){
				//特殊关键词可能需要添加Action后缀
				return array(
					'controller'=>$class_name,
					'action'=>$uri->action.'Action',
				);
			}else{
				throw new HttpException("Action \"{$uri->action}\" Not Found IN Controller \"{$class_name}\"");
			}
		}
		
		//访问地址不存在
		throw new HttpException("Controller \"{$uri->controller}\" Not Found");
	}
}