var post = {
    'postId': 0,
    'boxes': [],
    'roleCats': null,
    /**
     * 设置缩略图
     */
    'thumbnail': function(){
        system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
            uploader.image({
                'cat': 'post'
            });
        });
    },
    /**
     * 附件
     */
    'files': function(){
        system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
            uploader.files({
                'cat': 'post'
            });
        });
    },
    /**
     * 修改主分类
     */
    'changeCategory': function(){
        $(document).on('change', '[name="cat_id"]', function(){
            //更新附加属性
            var $boxProps = $('#box-props');
            if($boxProps.length){
                $boxProps.block();
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/post/get-prop-box', {
                        'cat_id': $(this).val(),
                        'post_id': post.postId
                    }),
                    'cache': false,
                    'success': function(resp){
                        $boxProps.unblock();
                        $boxProps.replaceWith(resp);
                    }
                });
            }
            //更新附加分类
            var $boxCategory = $('#box-category');
            if($boxCategory.length){
                $boxCategory.find('input').each(function(){
                    //标有data-disabled的为权限不允许编辑的分类
                    if(!$(this).attr('data-disabled')){
                        $(this).attr('disabled', false);
                    }
                });
                $boxCategory.find('[name="post_category[]"][value="'+$(this).val()+'"]').attr({
                    'checked':'checked',
                    'disabled':'disabed'
                });
            }
        });
    },
    'events': function(){
        $('#box-operation').on('click', '#edit-status-link', function(){
            $('#edit-status-container').show();
        }).on('click', '#set-status-editing', function(){
            var $editStatusSelector = $('#edit-status-selector');
            var status = $editStatusSelector.val();
            var status_text = $editStatusSelector.find('[value="'+status+'"]').text();
            $('input[name="status"]').val(status);
            $('#crt-status').text(status_text);
            $('#edit-status-container').hide();
        }).on('click', '#cancel-status-editing', function(){
            $('#edit-status-container').hide();
        });
    },
    'autosize': function(){
        if(!$('textarea.autosize').length && system.inArray('files', post.boxes)){
            //页面没有autosize输入框，且附件box存在，则引入autosize输入框插件
            system.getScript(system.assets('js/autosize.min.js'));
        }
    },
    'setRoleCats': function(){
        if(system.inArray('main_category', post.boxes)){
            $('#box-main-category').find('[name="cat_id"] option').each(function(){
                if(!system.inArray($(this).val(), post.roleCats)){
                    $(this).attr('disabled', 'disabled');
                }
            });
        }
        if(system.inArray('category', post.boxes)){
            $('#box-category').find('[name="post_category[]"]').each(function(){
                if(!system.inArray($(this).val(), post.roleCats)){
                    $(this).attr('disabled', 'disabled');
                    $(this).attr('data-disabled', 'data-disabled');
                }
            });
        }
    },
    /**
     * 弹出历史列表
     */
    'history': function(){
        //弹窗
        system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
            system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
                $('.show-post-history-link').fancybox({
                    'padding': 0,
                    'titleShow': false,
                    'centerOnScroll': true,
                    'onStart': function(o){
                        post.showHistoryList(0);
                    }
                });
            });
        });
        
        //绑定预览事件
        $(document).on('click', '.history-list li', function(){
            $(this).addClass('crt').siblings().removeClass('crt');
            $('#history-preview').attr('src', system.url('cms/admin/post-history/item', {
                'history_id': $(this).attr('data-id')
            }));
        });
    },
    /**
     * 获取并渲染历史记录列表（支持分页）
     * @param lastId 当前最后一条历史记录
     */
    'showHistoryList': function(lastId){
        if(!lastId){
            lastId = 0;
        }

        var $historyDialog = $('#history-dialog');
        if(lastId == 0){
            //清空之前可能存在的列表
            $historyDialog.find('.history-list').html('');
        }
        $.ajax({
            'type': 'GET',
            'url': system.url('cms/admin/post-history/list'),
            'data': {
                'last_id': lastId,
                'post_id': post.postId
            },
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                if(resp.status == 1){
                    if(resp.data.histories.length){
                        $.each(resp.data.histories, function(i, data){
                            $historyDialog.find('.history-list').append([
                                '<li class="', i ? '' : 'crt', '" data-id="', data.id, '">',
                                    '<div class="time"><abbr>', system.date(data.create_time), '</abbr></div>',
                                    '<div class="user"><span>', data.user.user.nickname ? data.user.user.nickname : data.user.user.username, '</span></div>',
                                '</li>'
                            ].join(''))
                        });
                        if(lastId == 0){
                            //加载首页历史的时候，将第一条历史预览出来
                            $historyDialog.find('.history-list li:first').click();
                        }
                    }else{
                        $historyDialog.find('.history-list').append([
                            '<li>',
                                '<div class="center"><span>没有了</span></div>',
                            '</li>'
                        ].join(''))
                    }
                }else{
                    common.alert(resp.message);
                }
            }
        });
    },
    'init': function(){
        if(system.inArray('thumbnail', post.boxes)){
            this.thumbnail();
        }
        if(system.inArray('files', post.boxes)){
            this.files();
        }
        if(system.inArray('tags', post.boxes)){
            system.getScript(system.assets('faycms/js/fayfox.textext.js'), function(){
                $('#tags').ftextext({
                    'url':system.url('cms/admin/tag/search'),
                    'width':'100%'
                });
            });
        }
        if(system.inArray('main_category', post.boxes)){
            this.changeCategory();
        }
        if(this.roleCats !== null){
            this.setRoleCats();
        }
        this.events();
        this.autosize();
        this.history();
    }
};