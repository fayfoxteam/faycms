<?php
namespace fay\core;

use fay\helpers\RuntimeHelper;

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
        RuntimeHelper::append(__FILE__, __LINE__, '路由解析完成');
        
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
                $filename = md5(\F::config()->get('base_url') . json_encode(Input::getInstance()->get(isset($cache_routers[$uri->router]['params']) ? $cache_routers[$uri->router]['params'] : array())));
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
        RuntimeHelper::append(__FILE__, __LINE__, '获取控制器名称');

        /**
         * @var $controller Controller
         */
        $controller = new $file['controller'];
        RuntimeHelper::append(__FILE__, __LINE__, '控制器被实例化');
        //触发事件
        \F::event()->trigger('after_controller_constructor');
        $content = $controller->{$file['action']}();
        RuntimeHelper::append(__FILE__, __LINE__, '控制器方式执行完毕');

        $this->response($controller->response, $content);
    }

    /**
     * 输出返回
     * @param Response $response
     * @param mixed $content
     */
    private function response($response, $content){
        if($content){
            //若未指定返回格式，则根据$content类型猜测一个格式
            if(!$response->getFormat()){
                if(is_string($content)){
                    //若是字符串，默认为HTML
                    $response->setFormat(Response::FORMAT_HTML);
                }else{
                    if(\F::input()->get('callback')){
                        //若非字符串，有callback参数，则默认为jsonp
                        $response->setFormat(Response::FORMAT_JSONP);
                    }else{
                        //默认为json
                        $response->setFormat(Response::FORMAT_JSON);
                    }
                }
            }
            
            //设置响应内容
            if(in_array($response->getFormat(), array(
                Response::FORMAT_JSON, Response::FORMAT_JSONP
            ))){
                if(is_object($content) && $content instanceof JsonResponse){
                    $response->setStatusCode($content->getHttpCode());
                    
                    if($content->getCallback()){
                        $response->setFormat(Response::FORMAT_JSONP)
                            ->setData(array(
                                'callback'=>$content->getCallback(),
                                'data'=>$content->toArray(),
                            ));
                    }else{
                        $response->setData($content->toArray());
                    }
                }else{
                    $response->setData($content);
                }
            }else{
                $response->setContent($content);
            }
        }
        
        //发送响应
        $response->send();
    }
    
    /**
     * 查找对应的controller文件和action方法
     * @param Uri $uri
     * @return array
     * @throws HttpException
     */
    private function getControllerAndAction($uri){
        //包名指定的是app
        if($uri->package == APPLICATION){
            $found_controllers = array();
            //在app目录下查找
            if(file_exists(APPLICATION_PATH . "modules/{$uri->module}/controllers/" . ucfirst($uri->controller) . 'Controller.php')){
                $class_name = '\\'.APPLICATION.'\modules\\'.$uri->module.'\controllers\\'.$uri->controller.'Controller';
                $found_controllers[] = $class_name;
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
                }
            }
            
            //若设置了addressing_path，在对应目录下查找
            $addressing_path = \F::config()->get('addressing_path');
            foreach($addressing_path as $address){
                if(file_exists(FAYSOFT_PATH . "{$address}/modules/{$uri->module}/controllers/" . ucfirst($uri->controller) . 'Controller.php')){
                    $class_name = "\\{$address}\\modules\\{$uri->module}\\controllers\\{$uri->controller}Controller";
                    $found_controllers[] = $class_name;
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
                    }
                }
            }
            
            if($found_controllers){
                throw new HttpException("Found the following controllers, but no Action {$uri->action} found among them.", 404, implode("\n", $found_controllers));
            }
        }
        
        //包名指定的是faysoft下的项目
        if(file_exists(FAYSOFT_PATH . "{$uri->package}/modules/{$uri->module}/controllers/". ucfirst($uri->controller) . 'Controller.php')){
            $class_name = "{$uri->package}\\modules\\".$uri->module.'\controllers\\'.$uri->controller.'Controller';
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