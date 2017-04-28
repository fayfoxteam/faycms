<?php
use cms\models\tables\PostsTable;
use cms\models\tables\RolesTable;
use cms\services\file\FileService;
use cms\services\user\UserRoleService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $posts array
 */
?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">标题</label>
            <?php echo F::form('widget')->inputText('title', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">有些主题可能用到，可留空</p>
        </div>
        <div class="form-field">
            <label class="title bold">文章列表</label>
            <a href="javascript:" class="btn select-post-link">选择文章</a>
            <div class="dragsort-list" id="widget-posts-post-list">
            <?php foreach($widget->config['posts'] as $p){?>
                <?php
                    //若文章已被永久删除，无法显示，直接跳过
                    if(!isset($posts[$p['post_id']])){
                        continue;
                    }
                ?>
                <div class="dragsort-item cf <?php if($posts[$p['post_id']]['status'] != PostsTable::STATUS_PUBLISHED || $posts[$p['post_id']]['delete_time']){
                    //未发布或已删除
                    echo 'bl-blue';
                }else if(!empty($p['end_time']) && \F::app()->current_time > $p['end_time']){
                    //已结束
                    echo 'bl-red';
                }else if(!empty($p['start_time']) && \F::app()->current_time < $p['start_time']){
                    //未开始
                    echo 'bl-yellow';
                }?>" id="posts-list-post-<?php echo $p['post_id']?>" data-id="<?php echo $p['post_id']?>">
                    <?php echo HtmlHelper::inputHidden('posts[]', $p['post_id'])?>
                    <a class="dragsort-rm" href="javascript:"></a>
                    <a class="dragsort-item-selector"></a>
                    <div class="dragsort-item-container">
                        <span class="fl post-thumbnail">
                            <a title="<?php echo HtmlHelper::encode($posts[$p['post_id']]['title'])?>" href="<?php echo $posts[$p['post_id']]['thumbnail'] ? FileService::getUrl($posts[$p['post_id']]['thumbnail']) : 'javascript:'?>" <?php if($posts[$p['post_id']]['thumbnail']) echo 'data-fancybox="images"'?>>
                                <?php echo HtmlHelper::img($posts[$p['post_id']]['thumbnail'], FileService::PIC_THUMBNAIL, array(
                                    'width'=>100,
                                    'height'=>100,
                                    'spare'=>'default',
                                ))?>
                            </a>
                        </span>
                        <div class="ml120">
                            <h3 class="post-title"><?php echo HtmlHelper::encode($posts[$p['post_id']]['title'])?></h3>
                            <div class="mt6 mb10 fc-grey">
                                <span class="mr10"><i class="fa fa-calendar mr5"></i><span class="post-time"><?php echo DateHelper::format($posts[$p['post_id']]['publish_time'])?></span></span>
                                <span class="mr10"><i class="fa fa-align-right mr5"></i><span class="post-cat"><?php echo HtmlHelper::encode($posts[$p['post_id']]['cat_title'])?></span></span>
                                <span class="mr10"><i class="fa fa-user mr5"></i><span class="post-author"><?php echo HtmlHelper::encode($posts[$p['post_id']]['nickname'])?></span></span>
                            </div>
                            <input name="start_time[<?php echo $p['post_id']?>]" type="text" value="<?php echo $p['start_time'] ? date('Y-m-d H:i:s', $p['start_time']) : ''?>" class="datetimepicker form-control wp49 fl" placeholder="生效时间" autocomplete="off">
                            <input name="end_time[<?php echo $p['post_id']?>]" type="text" value="<?php echo $p['end_time'] ? date('Y-m-d H:i:s', $p['end_time']) : ''?>" class="datetimepicker form-control wp49 fr" placeholder="过期时间" autocomplete="off">
                        </div>
                    </div>
                </div>
            <?php }?>
            </div>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
            <span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">发布时间格式</label>
                <?php echo F::form('widget')->inputText('date_format', array(
                    'class'=>'form-control mw150',
                ), 'pretty')?>
                <p class="fc-grey">若为空，则不显示时间；若为pretty，则会显示“1天前”这样的时间格式；<br>
                    其他格式视为PHP date函数的第一个参数</p>
            </div>
            <div class="form-field">
                <label class="title bold">文章缩略图尺寸</label>
                <?php
                echo F::form('widget')->inputText('post_thumbnail_width', array(
                    'placeholder'=>'宽度',
                    'class'=>'form-control w100 ib',
                )),
                ' x ',
                F::form('widget')->inputText('post_thumbnail_height', array(
                    'placeholder'=>'高度',
                    'class'=>'form-control w100 ib',
                ));
                ?>
                <p class="fc-grey">若留空，则返回默认尺寸缩略图。</p>
            </div>
            <div class="form-field">
                <label class="title bold">附加字段</label>
                <?php
                echo F::form('widget')->inputCheckbox('fields[]', 'category', array(
                    'label'=>'分类详情',
                ), true);
                echo F::form('widget')->inputCheckbox('fields[]', 'user', array(
                    'label'=>'作者信息',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'files', array(
                    'label'=>'附件',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'meta', array(
                    'label'=>'计数（评论数/阅读数/点赞数）',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'tags', array(
                    'label'=>'标签',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'props', array(
                    'label'=>'附加属性',
                ));
                ?>
                <p class="fc-grey">仅勾选模版中用到的字段，可以加快程序效率。</p>
            </div>
            <div class="form-field thumbnail-size-container <?php if(empty($widget->config['fields']) || !in_array('files', $widget->config['fields']))echo 'hide';?>">
                <label class="title bold">附件缩略图尺寸</label>
                <?php
                echo F::form('widget')->inputText('file_thumbnail_width', array(
                    'placeholder'=>'宽度',
                    'class'=>'form-control w100 ib',
                )),
                ' x ',
                F::form('widget')->inputText('file_thumbnail_height', array(
                    'placeholder'=>'高度',
                    'class'=>'form-control w100 ib',
                ));
                ?>
                <p class="fc-grey">若留空，则默认为100x100。</p>
            </div>
            <?php F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>
<script>
$(function(){
    var widgetPosts = {
        /**
         * 选取一篇文章
         */
        'addPost': function(postId){
            if($('#posts-list-post-'+postId).length){
                common.alert('已在列表中，不能重复添加');
                return false;
            }
            
            //添加拖拽结构
            $('#widget-posts-post-list').prepend([
                '<div class="dragsort-item cf" id="posts-list-post-', postId, '" data-id="', postId, '">',
                    '<input type="hidden" name="posts[]" value="', postId, '">',
                    '<a class="dragsort-rm" href="javascript:"></a>',
                    '<a class="dragsort-item-selector"></a>',
                    '<div class="dragsort-item-container">',
                        '<span class="fl post-thumbnail">',
                            '<a title="" href="" data-fancybox="images">',
                                '<img src="', system.assets('images/loading.gif'), '" width="100" height="100" />',
                            '</a>',
                        '</span>',
                        '<div class="ml120">',
                            '<h3 class="post-title">加载中...</h3>',
                            '<div class="mt6 mb10 fc-grey">',
                                '<span class="mr10"><i class="fa fa-calendar mr5"></i><span class="post-time"></span></span>',
                                '<span class="mr10"><i class="fa fa-align-right mr5"></i><span class="post-cat"></span></span>',
                                '<span class="mr10"><i class="fa fa-user mr5"></i><span class="post-author"></span></span>',
                            '</div>',
                            '<input name="start_time[', postId, ']" type="text" value="" class="datetimepicker form-control wp49 fl" placeholder="生效时间" autocomplete="off">',
                            '<input name="end_time[', postId, ']" type="text" value="" class="datetimepicker form-control wp49 fr" placeholder="过期时间" autocomplete="off">',
                        '</div>',
                    '</div>',
                '</div>'
            ].join(''));
            
            //拉取文章详细信息
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/api/post/get'),
                'data': {
                    'id': postId,
                    'fields': 'id,title,publish_time,thumbnail,user.nickname,category.title'
                },
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    if(resp.status){
                        var $item = $('#posts-list-post-'+postId);
                        var post = resp.data.post;
                        $item.find('.post-thumbnail a').attr({
                            'href': post.thumbnail.url,
                            'title': system.encode(post.title),
                            'data-caption': system.encode(post.title)
                        });
                        $item.find('.post-thumbnail a img').attr('src', post.thumbnail.thumbnail);
                        $item.find('.post-title').text(post.title);
                        $item.find('.post-time').text(system.date(post.publish_time));
                        $item.find('.post-cat').text(resp.data.category.title);
                        $item.find('.post-author').text(resp.data.user.user.nickname);
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        },
        'selectPost': function(){
            system.getScript(system.url('assets/faycms/js/admin/jquery.selectPost.js'), function(){
                $('.select-post-link').selectPost({
                    'onSelect': function(o){
                        var postId = o.attr('data-id');
                        o.remove();
                        
                        widgetPosts.addPost(postId);
                    },
                    'onDataLoaded': function(){
                        var $selectPostDialog = $('#select-post-dialog');
                        $('#widget-posts-post-list').find('.dragsort-item').each(function(){
                            $selectPostDialog.find('#select-post-'+$(this).attr('data-id')).find('.select-single-post').remove();
                        });
                    }
                });
            });
        },
        /**
         * 显示/隐藏附件图片尺寸
         */
        'fileParams': function(){
            $('input[name="fields[]"][value="files"]').on('click', function(){
                if($(this).is(':checked')){
                    $('.thumbnail-size-container').show();
                }else{
                    $('.thumbnail-size-container').hide();
                }
            });
        },
        'init': function(){
            this.selectPost();
        }
    };
    
    widgetPosts.init();
});
</script>