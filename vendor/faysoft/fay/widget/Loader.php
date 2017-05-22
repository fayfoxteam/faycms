<?php
namespace fay\widget;

use cms\services\widget\WidgetAreaService;
use fay\core\Exception;
use fay\core\HttpException;
use fay\helpers\RuntimeHelper;
use cms\models\tables\WidgetsTable;
use fay\helpers\StringHelper;
use fay\helpers\UrlHelper;

class Loader{
    private static $_instance;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 实例化一个widget，并返回实例
     * @param $name
     * @param string $controller 不到Controller后缀的控制器名称，
     * @return Widget
     */
    public function get($name, $controller = 'Index'){
        $controller = ucfirst($controller) . 'Controller';
        if(strpos($name, '/') === false){
            //用户自定义的widget name不带斜杠
            $class_name = APPLICATION.'\widgets\\'.$name.'\controllers\\'.$controller;
            $path = APPLICATION_PATH . 'widgets/' . $name . '/';
            if(file_exists("{$path}controllers/{$controller}.php")){
                return new $class_name($name, $path);
            }else{
                return null;
            }
        }else{
            //系统自带的widget name为cms/*或者fay/*
            $name_explode = explode('/', $name);
            $pre = array_shift($name_explode);//pre取值为cms或fay
            $class_name = $pre.'\widgets\\'.implode('\\', $name_explode).'\\controllers\\'.$controller;
            $path = FAYSOFT_PATH . $pre . '/widgets/' . implode('/', $name_explode) . '/';
            if(file_exists("{$path}controllers/{$controller}.php")){
                return new $class_name($name, $path);
            }else{
                return null;
            }
        }
    }
    
    /**
     * 根据数据库中的别名，实例化对应的widget，进行渲染
     * @param int|string|array $widget
     *  - 若为数字，视为数据库中的ID
     *  - 若为字符串，视为别名
     *  - 若为数组，视为已经从数据库中搜出对应行
     * @param string $index 若为小工具域调用，则此参数为本小工具在小工具域中的位置
     * @param bool $ajax 是否ajax调用，若为null，默认采用widgets表设置。若存在启用缓存，则不会走ajax。
     * @param string $action
     * @throws HttpException
     * @throws \fay\core\ErrorException
     */
    public function load($widget, $index = null, $ajax = null, $action = 'index'){
        if(!is_array($widget)){
            if(StringHelper::isInt($widget)){
                $widget = WidgetsTable::model()->find($widget);
            }else{
                $widget = WidgetsTable::model()->fetchRow(array(
                    'alias = ?'=>$widget,
                ));
            }
        }
        
        if($widget && $widget['enabled']){
            if($ajax === null){
                $ajax = $widget['ajax'];
            }
            if($widget['cache'] >= 0 && $content = \F::cache()->get('widgets/' . $widget['alias'])){
                echo $content;
            }else{
                RuntimeHelper::append(__FILE__, __LINE__, "准备渲染小工具: {$widget['alias']}");
                $this->render($widget['widget_name'], json_decode($widget['config'], true), $ajax, $widget['cache'], $widget['alias'], $index, $action);
                RuntimeHelper::append(__FILE__, __LINE__, "小工具: {$widget['alias']}渲染完成");
            }
        }else{
            throw new HttpException('Widget不存在或已被删除');
        }
    }
    
    /**
     * 根据widget name调用index方法，直接渲染一个widget
     * @param string $name 小工具名称
     * @param array $options 小工具配置参数
     * @param bool $ajax 是否ajax调用
     * @param int $cache 缓存时间，若为负数，则不缓存，为0则缓存不过期。若开启缓存，则ajax参数无效
     * @param string $alias 别名，若直接调用，则别名为空
     * @param string $index 若为小工具域调用，则此参数为本小工具在小工具域中的位置
     * @param string $action
     * @throws \fay\core\ErrorException
     */
    public function render($name, $options = array(), $ajax = false, $cache = -1, $alias = '', $index = null, $action = 'index'){
        if($alias && $cache >= 0 && $content = \F::cache()->get('widgets/' . $alias)){
            echo $content;
        }else{
            $real_action = StringHelper::hyphen2case($action, false);
            $widget_obj = $this->get($name);
            $widget_obj->initConfig($options);
            if($widget_obj == null){
                echo 'widget不存在或已被删除';
            }else if(!method_exists($widget_obj, $real_action) && !method_exists($widget_obj, $real_action.'Action')){
                echo 'widget方法不存在';
            }else{
                $widget_obj->alias = $alias;//别名
                $widget_obj->_index = $index;//在小工具域中的位置，若不是小工具域中调用，则为null
                if($ajax){
                    //先占个位，然后用ajax引入widget
                    $id = uniqid();
                    if(method_exists($widget_obj, 'placeholder')){
                        //若定义了占位式样，则显示定义的式样
                        echo "<div id='{$id}'>";
                        $widget_obj->placeholder();
                        echo '</div>';
                    }else{
                        //若未定义，显示一个loading的图片
                        echo "<div id='{$id}'><img src='".\F::app()->view->assets('images/throbber.gif')."' /></div>";
                    }
                    
                    if($alias){
                        echo '<script>
                        $(function(){
                            $.ajax({
                                type: "GET",
                                url: "'.UrlHelper::createUrl('widget/load', array(
                                    'alias'=>$widget_obj->alias,
                                    'action'=>$action,
                                    '_index'=>$widget_obj->_index,
                                )).'",
                                cache: false,
                                success: function(resp){
                                    $("#'.$id.'").replaceWith(resp);
                                }
                            });
                        });
                        </script>';
                    }else{
                        echo '<script>
                        $(function(){
                            $.ajax({
                                type: "GET",
                                url: "'.UrlHelper::createUrl('widget/render', array(
                                    'name'=>$name,
                                    'action'=>$action,
                                    '_alias'=>$widget_obj->alias,
                                    '_index'=>$widget_obj->_index
                                ) + $options).'",
                                cache: false,
                                success: function(resp){
                                    $("#'.$id.'").replaceWith(resp);
                                }
                            });
                        });
                        </script>';
                    }
                }else{
                    ob_start();
                    if(method_exists($widget_obj, $real_action)){
                        $widget_obj->{$real_action}();
                    }else{
                        $widget_obj->{$real_action.'Action'}();
                    }
                    $content = ob_get_contents();
                    ob_end_clean();
                    if($cache >= 0 && $alias){
                        \F::cache()->set('widgets/' . $alias, $content, $cache);
                    }
                    echo $content;
                }
            }
        }
    }
    
    /**
     * 返回小工具参数
     * @param string $alias 小工具实例别名
     * @throws HttpException
     */
    public function getData($alias){
        $widget_config = WidgetsTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ));
        
        if(!$widget_config){
            throw new HttpException('Widget不存在或已被删除');
        }
        
        if($widget_config['enabled']){
            //获取widget实例
            $widget_obj = $this->get($widget_config['widget_name']);
            //初始化配置
            $widget_obj->initConfig(json_decode($widget_config['config'], true));
            if($widget_obj == null){
                throw new HttpException('Widget不存在或已被删除');
            }
            if(method_exists($widget_obj, 'getData')){
                return $widget_obj->getData();
            }else{
                throw new HttpException('小工具未实现获取数据方法');
            }
        }else{
            throw new HttpException('小工具未启用');
        }
    }
    
    /**
     * 渲染一个小工具域
     * @param string $alias 小工具域别名
     * @throws Exception
     */
    public function area($key){
        $widgets = WidgetAreaService::service()->getWidgets($key);
        
        foreach($widgets as $k => $w){
            $this->load($w, $k+1);
        }
    }
}