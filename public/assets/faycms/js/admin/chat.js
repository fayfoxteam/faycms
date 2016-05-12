var chat = {
	'status':{},
	'display_name':'username',
	'parmission':{},
	/**
	 * 获取一个会话，并调用渲染函数进行渲染
	 */
	'getChat':function(id){
		$.ajax({
			'type': 'GET',
			'url': system.url('admin/chat/item', {
				'id':id
			}),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('#chat-dialog').unblock();
				if(resp.status){
					chat.showRoot(resp.data.root);
					chat.showReplies(resp.data.replies);
					
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
	'showRoot':function(root){
		$('#chat-dialog .cd-user').text(root[chat.display_name]);
		$('#chat-dialog .cd-to').text(root['to_user_id_'+chat.display_name]);
		$('#chat-dialog .cd-time').text(system.date(root.create_time));
		$('#chat-dialog .cd-content').html(system.encode(root.content).replace(/\n/g, '<br />'));
		$('#chat-dialog .cd-avatar').attr('src', system.url('file/pic', {
			'f':root.avatar,
			's':'avatar',
			't':2
		}));
		$('#chat-dialog [name="content"]').attr('placeholder', '回复 '+root[chat.display_name]);
		$('#chat-dialog [name="to_user_id"]').val(root.to_user_id);
		$('#chat-dialog [name="parent"]').val(root.id);
		$('#chat-dialog .reply-root').attr('data-username', root[chat.display_name]);
		$('#chat-dialog .reply-root').attr('data-id', root.id);
	},
	/**
	 * 显示回复
	 */
	'showReplies':function(replies){
		$.each(replies, function(i, data){
			$('#chat-dialog .cd-timeline').append(chat.showOneReply(data));
		});
	},
	'showOneReply':function(data){
		return ['<li class="cd-item" id="reply-', data.id, '">',
			'<div class="cdi-line"></div>',
			'<div class="cdi-header">',
			'<span class="cdi-user">', data[chat.display_name], '</span>',
			' 回复 ',
			'<span class="cdi-user">', data['parent_'+chat.display_name], '</span>',
				'<span class="cdi-time" title="', system.date(data.create_time), '">',
					system.shortDate(data.create_time),
				'</span>',
				'<span class="cdi-status">', chat.status[data.status], '</span>',
			'</div>',
			'<div class="cdi-content">', system.encode(data.content).replace(/\n/g, '<br />'), '</div>',
			'<div class="cdi-options">',
			(function(){
				return chat.permissions.approve ?
					['<a href="javascript:;" class="reply-link" data-id="', data.id, '" data-username="', data[chat.display_name], '">',
						'<i class="icon-reply"></i>回复',
					'</a>'].join('') : ''
			}()),
			(function(){
				return chat.permissions.approve ?
					['<a href="javascript:;" class="fc-green approve-link', data.status == chat.status.approved ? ' hide' : '', '" data-id="', data.id, '">',
						'<i class="icon-eye"></i>批准',
					'</a>'].join('') : ''
			}()),
			(function(){
				return chat.permissions.unapprove ?
					['<a href="javascript:;" class="fc-orange unapprove-link', data.status == chat.status.unapproved ? ' hide' : '', '" data-id="', data.id, '">',
						'<i class="icon-eye-slash"></i>驳回',
					'</a>'].join('') : ''
			}()),
			(function(){
				return chat.permissions['delete'] ?
					['<a href="javascript:;" class="fc-red delete-link" data-id="', data.id, '">',
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
		$('#chat-dialog .cd-user').text('');
		$('#chat-dialog .cd-to').text('');
		$('#chat-dialog .cd-time').text('');
		$('#chat-dialog .cd-content').html('');
		$('#chat-dialog .cd-timeline').html('');
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
					'centerOnScroll':true,
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
					'url': system.url('admin/message/remove-all', {
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
		});
		
		$('.chats-list').on('click', '.approve-link', function(){
			$('#chat-'+$(this).attr('data-id')).block();
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/message/approve', {
					'id':$(this).attr('data-id')
				}),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('#chat-'+resp.data.id).unblock();
					if(resp.status){
						$('#chat-'+resp.data.id).find('.ci-status').html(chat.status[resp.data.status]);
						$('#chat-'+resp.data.id).find('.approve-link').hide()
						$('#chat-'+resp.data.id).find('.unapprove-link').show();
					}else{
						common.alert(resp.message);
					}
				}
			});
		});
		
		$('.chats-list').on('click', '.unapprove-link', function(){
			$('#chat-'+$(this).attr('data-id')).block();
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/message/unapprove', {
					'id':$(this).attr('data-id')
				}),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('#chat-'+resp.data.id).unblock();
					if(resp.status){
						$('#chat-'+resp.data.id).find('.ci-status').html(chat.status[resp.data.status]);
						$('#chat-'+resp.data.id).find('.unapprove-link').hide()
						$('#chat-'+resp.data.id).find('.approve-link').show();
					}else{
						common.alert(resp.message);
					}
				}
			});
		});
		
		$('.chats-list').on('click', '.delete-link', function(){
			$('#chat-'+$(this).attr('data-id')).block();
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/message/delete', {
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
		});
		
		$(document).on('click', '.cd-item .approve-link', function(){
			$('#reply-'+$(this).attr('data-id')).block({
				'zindex':1200
			});
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/message/approve', {
					'id':$(this).attr('data-id')
				}),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('#reply-'+resp.data.id).unblock();
					if(resp.status){
						$('#reply-'+resp.data.id).find('.cdi-status').html(chat.status[resp.data.id]);
						$('#reply-'+resp.data.id).find('.approve-link').hide()
						$('#reply-'+resp.data.id).find('.unapprove-link').show();
					}else{
						common.alert(resp.message);
					}
				}
			});
		});
		
		$(document).on('click', '.cd-item .unapprove-link', function(){
			$('#reply-'+$(this).attr('data-id')).block({
				'zindex':1200
			});
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/message/unapprove', {
					'id':$(this).attr('data-id')
				}),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('#reply-'+resp.data.id).unblock();
					if(resp.status){
						$('#reply-'+resp.data.id).find('.cdi-status').html(chat.status[resp.data.id]);
						$('#reply-'+resp.data.id).find('.unapprove-link').hide()
						$('#reply-'+resp.data.id).find('.approve-link').show();
					}else{
						common.alert(resp.message);
					}
				}
			});
		});
		
		$(document).on('click', '.cd-item .delete-link', function(){
			if(confirm('确定要将该条留言放入回收站吗？')){
				$('#reply-'+$(this).attr('data-id')).block({
					'zindex':1200
				});
				$.ajax({
					'type': 'GET',
					'url': system.url('admin/message/delete', {
						'id':$(this).attr('data-id')
					}),
					'dataType': 'json',
					'cache': false,
					'success': function(resp){
						$('#reply-'+resp.data.id).unblock();
						if(resp.status){
							$('#reply-'+resp.data.id).fadeOut('normal', function(){
								$(this).remove();
							});
						}else{
							common.alert(resp.message);
						}
					}
				});
			}
		});
		
		$(document).on('click', '#chat-dialog .reply-link', function(){
			$('#chat-dialog [name="parent"]').val($(this).attr('data-id'));
			$('#chat-dialog [name="content"]').attr('placeholder', '回复 '+$(this).attr('data-username')).focus();
		});
		
		$(document).on('submit', '#reply-form', function(){
			$('#chat-dialog').block({
				'zindex':1200
			});
			$.ajax({
				'type': 'POST',
				'url': system.url('admin/message/create'),
				'data':$('#reply-form').serialize(),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('#chat-dialog').unblock();
					if(resp.status){
						$('#chat-dialog [name="content"]').val('');
						$('#chat-dialog .cd-timeline').prepend(chat.showOneReply(resp.data));
						$("#chat-dialog .cd-reply-list").slimScroll({
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