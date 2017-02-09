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
return array();