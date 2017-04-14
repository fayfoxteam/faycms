var chat = {
    'status':{},
    'display_name':'username',
    'permissions':{},
    /**
     * 获取一个会话，并调用渲染函数进行渲染
     */
    'getChat':function(id){
        $.ajax({
            'type': 'GET',
            'url': system.url('cms/admin/message/item', {
                'id':id
            }),
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                $('#chat-dialog').unblock();
                if(resp.status){
                    chat.showMessage(resp.data.message);
                    chat.showReplies(resp.data.children);
                    
                    system.getScript(system.assets('js/jquery.slimscroll.min.js'), function(){
                        $("#chat-dialog .cd-reply-list").slimScroll({
                            'color':'#a8d3f6',
                            'distance':'8px',
                            'opacity':.7,
                            'alwaysVisible':true
                        });
                        $.fancybox.center(true);
                    });
                }else{
                    common.alert(resp.message);
                }
            }
        });
    },
    /**
     * 显示根留言
     */
    'showMessage':function(message){
        $chatDialog = $('#chat-dialog');
        $chatDialog.find('.cd-user').text(message.user.user[chat.display_name]);
        $chatDialog.find('.cd-to').text(message.to_user.user[chat.display_name]);
        $chatDialog.find('.cd-time').text(system.date(message.message.create_time));
        $chatDialog.find('.cd-content').html(system.encode(message.message.content));
        $chatDialog.find('.cd-avatar').attr('src', message.user.user.avatar_url);
        $chatDialog.find('[name="content"]').attr('placeholder', '回复 '+message.user.user[chat.display_name]);
        $chatDialog.find('[name="to_user_id"]').val(message.user.user.id);
        $chatDialog.find('[name="parent"]').val(message.message.id);
        $chatDialog.find('.reply-root').attr({
            'data-username': message.user.user[chat.display_name],
            'data-id': message.message.id
        });
    },
    /**
     * 显示回复
     */
    'showReplies':function(replies){
        var $container = $('#chat-dialog .cd-timeline');
        $.each(replies.messages, function(i, data){
            $container.append(chat.showOneReply(data));
        });
    },
    'showOneReply':function(data){
        return ['<li class="cd-item" id="reply-', data.message.id, '">',
            '<div class="cdi-line"></div>',
            '<div class="cdi-header">',
            '<span class="cdi-user">', data.user.user[chat.display_name], '</span>',
            ' 回复 ',
            '<span class="cdi-user">', data.parent.user.user[chat.display_name], '</span>',
                '<span class="cdi-time" title="', system.date(data.message.create_time), '">',
                    system.shortDate(data.message.create_time),
                '</span>',
                '<span class="cdi-status">', chat.status[data.message.status], '</span>',
            '</div>',
            '<div class="cdi-content">', system.encode(data.message.content), '</div>',
            '<div class="cdi-options">',
            (function(){
                return chat.permissions.approve ?
                    ['<a href="javascript:;" class="reply-link" data-id="', data.message.id, '" data-username="', data.user.user[chat.display_name], '">',
                        '<i class="icon-reply"></i>回复',
                    '</a>'].join('') : ''
            }()),
            (function(){
                return chat.permissions.approve ?
                    ['<a href="javascript:;" class="fc-green approve-link', data.message.status == chat.status.approved ? ' hide' : '', '" data-id="', data.message.id, '">',
                        '<i class="icon-eye"></i>批准',
                    '</a>'].join('') : ''
            }()),
            (function(){
                return chat.permissions.unapprove ?
                    ['<a href="javascript:;" class="fc-orange unapprove-link', data.message.status == chat.status.unapproved ? ' hide' : '', '" data-id="', data.message.id, '">',
                        '<i class="icon-eye-slash"></i>驳回',
                    '</a>'].join('') : ''
            }()),
            (function(){
                return chat.permissions['delete'] ?
                    ['<a href="javascript:;" class="fc-red delete-link" data-id="', data.message.id, '">',
                        '<i class="icon-trash"></i>回收站',
                    '</a>'].join('') : ''
            }()),
            '</div>',
        '</li>'].join('');
    },
    /**
     * 清空dialog数据
     */
    'reset':function(){
        var $chatDialog = $('#chat-dialog');
        $chatDialog.find('.cd-user').text('');
        $chatDialog.find('.cd-to').text('');
        $chatDialog.find('.cd-time').text('');
        $chatDialog.find('.cd-content').html('');
        $chatDialog.find('.cd-timeline').html('');
    },
    /**
     * 绑定回复事件
     */
    'reply':function(){
        system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
            system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
                $('.ci-reply-link').fancybox({
                    'padding':0,
                    'titleShow':false,
                    'onStart':function(){
                        chat.reset();
                    },
                    'onComplete':function(o){
                        $('#chat-dialog').block({
                            'zindex':1200
                        });
                        chat.getChat($(o).attr('data-id'));
                    }
                });
            });
        });
    },
    'events':function(){
        //删除会话
        $('.chats-list').on('click', '.remove-all-link', function(){
            if(confirm('该操作会永久删除整个会话（包括所有回复），您确定要继续吗？')){
                $('#chat-'+$(this).attr('data-id')).block();
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/message/remove-all', {
                        'id':$(this).attr('data-id')
                    }),
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        $('#chat-'+resp.data.id).unblock();
                        if(resp.status){
                            $('#chat-'+resp.data.id).fadeOut('normal', function(){
                                $(this).remove();
                            });
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            }
        }).on('click', '.approve-link', function(){
            $('#chat-'+$(this).attr('data-id')).block();
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/message/approve', {
                    'id':$(this).attr('data-id')
                }),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chat = $('#chat-'+resp.data.id);
                    $chat.unblock();
                    if(resp.status){
                        $chat.find('.ci-status').html(chat.status[resp.data.status]);
                        $chat.find('.approve-link').hide();
                        $chat.find('.unapprove-link').show();
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        }).on('click', '.unapprove-link', function(){
            $('#chat-'+$(this).attr('data-id')).block();
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/message/unapprove', {
                    'id':$(this).attr('data-id')
                }),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chat = $('#chat-'+resp.data.id);
                    $chat.unblock();
                    if(resp.status){
                        $chat.find('.ci-status').html(chat.status[resp.data.status]);
                        $chat.find('.unapprove-link').hide();
                        $chat.find('.approve-link').show();
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        }).on('click', '.delete-link', function(){
            $('#chat-'+$(this).attr('data-id')).block();
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/message/delete', {
                    'id':$(this).attr('data-id')
                }),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chat = $('#chat-'+resp.data.id);
                    $chat.unblock();
                    if(resp.status){
                        $chat.fadeOut('normal', function(){
                            $(this).remove();
                        });
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        });
        
        $(document).on('click', '.cd-item .approve-link', function(){
            $('#reply-'+$(this).attr('data-id')).block({
                'zindex':1200
            });
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/message/approve', {
                    'id':$(this).attr('data-id')
                }),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chat = $('#reply-'+resp.data.id);
                    $chat.unblock();
                    if(resp.status){
                        $chat.find('.cdi-status').html(chat.status[resp.data.id]);
                        $chat.find('.approve-link').hide();
                        $chat.find('.unapprove-link').show();
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        }).on('click', '.cd-item .unapprove-link', function(){
            $('#reply-'+$(this).attr('data-id')).block({
                'zindex':1200
            });
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/message/unapprove', {
                    'id':$(this).attr('data-id')
                }),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chat = $('#reply-'+resp.data.id);
                    $chat.unblock();
                    if(resp.status){
                        $chat.find('.cdi-status').html(chat.status[resp.data.id]);
                        $chat.find('.unapprove-link').hide();
                        $chat.find('.approve-link').show();
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        }).on('click', '.cd-item .delete-link', function(){
            if(confirm('确定要将该条留言放入回收站吗？')){
                $('#reply-'+$(this).attr('data-id')).block({
                    'zindex':1200
                });
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/message/delete', {
                        'id':$(this).attr('data-id')
                    }),
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        var $chat = $('#reply-'+resp.data.id);
                        $chat.unblock();
                        if(resp.status){
                            $chat.fadeOut('normal', function(){
                                $(this).remove();
                            });
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            }
        }).on('click', '#chat-dialog .reply-link', function(){
            $('#chat-dialog [name="parent"]').val($(this).attr('data-id'));
            $('#chat-dialog [name="content"]').attr('placeholder', '回复 '+$(this).attr('data-username')).focus();
        }).on('submit', '#reply-form', function(){
            $('#chat-dialog').block({
                'zindex':1200
            });
            $.ajax({
                'type': 'POST',
                'url': system.url('cms/admin/message/create'),
                'data':$('#reply-form').serialize(),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    var $chatDialog = $('#chat-dialog');
                    $chatDialog.unblock();
                    if(resp.status){
                        $chatDialog.find('[name="content"]').val('');
                        $chatDialog.find('.cd-timeline').prepend(chat.showOneReply(resp.data));
                        $chatDialog.find('.cd-reply-list').slimScroll({
                            'scrollTo':'0px',
                            'animate':true
                        });
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
            return false;
        });
    },
    'init':function(){
        this.events();
        this.reply();
    }
};