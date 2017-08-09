<?php
/**
 * # 事件
 * 事件会被绑定到特定的系统执行节点，并根据router信息进行匹配
 * 匹配到的事件被触发时会执行handler方法
 * {
 *     '格式':{
 *         'handler':{
 *             '描述':{
 *                 '类方法':'array($obj, 'run')',
 *                 '静态方法':'blog\plugins\AdminMenu::run' 或 array('blog\plugins\AdminMenu', 'run'),
 *                 '匿名函数':function(){...},
 *             },
 *             '必须':'是'
 *         }
 *         'router':{
 *             '描述':'用于匹配路由的正则，若不设置，所有请求都将执行此hook（特殊hook除外）',
 *             '必须':'否'
 *         }
 *     },
 *     '事件说明':{
 *         'after_uri':{
 *             '触发节点':'Uri实例化之后（即系统Url解析完毕）',
 *             '传入参数':{}
 *         },
 *         'after_controller_constructor':{
 *             '触发节点':'Controller被实例化之后执行',
 *             '传入参数':{}
 *         },
 *         'admin_before_post_create':{
 *             '触发节点':'创建文章之前',
 *             '传入参数':{
 *                 cat_id:'分类ID'
 *             }
 *         },
 *         'admin_after_post_created':{
 *             '触发节点':'创建文章之后',
 *             '传入参数':{
 *                 post_id:'文章ID'
 *             }
 *         },
 *         'admin_before_post_update':{
 *             '触发节点':'编辑文章之前（如果有post提交的话，实际上是完成文章更新之后执行）',
 *             '传入参数':{
 *                 post_id:'文章ID',
 *                 cat_id:'分类ID'
 *             }
 *         },
 *         'admin_after_post_updated':{
 *             '触发节点':'文章更新之后',
 *             '传入参数':{
 *                 post_id:'文章ID'
 *             }
 *         },
 *         'before_render':{
 *             '触发节点':'调用render方法前，若不调用render方法，不会执行该钩子',
 *             '传入参数':{}
 *         }
 *     }
 * }
 */
return array(
    \fay\core\Response::EVENT_BEFORE_SEND => array(
        array(
            /**
             * 若开启debug，则在返回中插入debug信息
             */
            'handler'=>function($data){
                /**
                 * @var $response \fay\core\Response
                 */
                $response = isset($data['response']) ? $data['response'] : \F::app()->response;
                if(\F::config()->get('debug')){
                    $format = $response->getFormat();
                    if(in_array($format, array(\fay\core\Response::FORMAT_JSON, \fay\core\Response::FORMAT_JSONP))){
                        //若是JSON，在返回结构中追加_debug信息
                        $data = $response->getData();
                        if(isset($data['status'])){
                            //认为是系统标准返回JSON格式，附加debug信息
                            $sqls = \fay\core\Db::getInstance()->getSqlLogs();
                            $sql_formats = array();
                            $sql_time = 0;
                            foreach($sqls as $s){
                                $sql_formats[] = array(
                                    'time'=>\fay\helpers\StringHelper::money($s[2] * 1000).'ms',
                                    'sql'=>\fay\helpers\SqlHelper::bind($s[0], $s[1]),
                                );
                                $sql_time += $s[2];
                            }
                            
                            $data['_debug'] = array(
                                'sqls'=>$sql_formats,
                                'sql_time'=>\fay\helpers\StringHelper::money($sql_time * 1000).'ms',
                                'php_time'=>\fay\helpers\StringHelper::money((microtime(true) - START) * 1000).'ms',
                                'memory'=>round(memory_get_usage()/1024, 2).'KB',
                            );
                            
                            $response->setData($data);
                        }
                    }else if($format == \fay\core\Response::FORMAT_HTML){
                        //若是html，在默认追加debug信息
                        $html = $response->getContent();
                        $html .= \F::app()->view->renderPartial('common/_debug');
                        $response->setContent($html);
                    }
                }
            },
        )
    )
);