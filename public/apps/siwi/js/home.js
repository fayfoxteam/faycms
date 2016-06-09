var home = {
	'loadScripts':function(){
		//遮蔽层
		system.getScript(system.assets('faycms/js/fayfox.block.js'));
		//滚动
		system.getScript(system.assets('js/jquery.scrollTo-min.js'));
	},
	//提交留言
	'submitCreateMessageForm':function(){
		$('.create-message-container').block();
		$.ajax({
			'type': 'POST',
			'url': system.url('user/message/create'),
			'data': $('#create-message-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('.create-message-container').unblock();
				if(resp.status){
					$(".create-message-container [name='content']").val('');
					var html = ['<li id="msg-', resp.data.id, '">',
						'<div class="avatar">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html">',
								'<img src="', system.url('file/pic', {
									't':2,
									's':'avatar',
									'f':resp.data.avatar
								}), '" />',
							'</a>',
						'</div>',
						'<div class="meta">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html" class="user-link">',
								resp.data.nickname,
							'</a>',
							'<time class="time">', resp.data.date, '</time>',
						'</div>',
						'<div class="message-content">', system.encode(resp.data.content), '</div>',
						'<ul class="children-list"></ul>',
						'<a href="javascript:;" title="回复" class="icon-reply reply-link" data-parent="', resp.data.id, '"></a>',
					'</li>'].join('');
					$(".message-container ul.message-list").prepend(html);
					$.scrollTo('.message-container', 500);
				}else{
					common.showError(resp.message);
				}
			}
		});
		return false;
	},
	'submitReplyMessageForm':function(){
		$(".reply-container").block();
		$.ajax({
			'type': 'POST',
			'url': system.url('user/message/create'),
			'data': $('#reply-message-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('.reply-container').unblock();
				if(resp.status){
					$('.reply-container').slideUp('normal', function(){
						$(this).remove();
					});
					var html = ['<li>',
						'<span class="un">',
							(function(data){
								if(data.parent_user_id == data.user_id){
									return ['<a href="', system.url('u/'+data.user_id+'.html'), '" title="', data.nickname, '">', data.nickname, '</a>', ' :'].join('');
								}else{
									return [
										'<a href="', system.url('u/'+data.user_id+'.html'), '" title="', data.nickname, '">', data.nickname, '</a>',
										' 回复 ',
										'<a href="', system.url('u/'+data.parent_user_id+'.html'), '" title="', data.parent_nickname, '">', data.parent_nickname, '</a>',
										' :',
									].join('');
								}
							}(resp.data)),
						'</span>',
						'<p>', system.encode(resp.data.content), '</p>',
						'<time>', resp.data.date, '</time> ',
						'<a href="javascript:;" title="回复" class="icon-comment reply-child-link" data-parent="', resp.data.id, '"></a>',
					'</li>'].join('');
					
					$('#msg-'+resp.data.root+' .children-list').append(html);
					
				}else{
					common.showError(resp.message);
				}
			}
		});
		return false;
	},
	'events':function(){
		//提交留言
		$(document).on('submit', '#create-message-form', function(){
			home.submitCreateMessageForm();
			return false;
		});
		
		//给出回复界面
		$(document).on('click', '.reply-link', function(){
			$('.reply-container').slideUp('normal', function(){
				$(this).remove();
			});
			var html = ['<div class="reply-container hide">',
				'<form id="reply-message-form">',
					'<input type="hidden" name="target" value="', home.user_id, '" />',
					'<input type="hidden" name="parent" value="', $(this).attr("data-parent"), '" />',
					'<textarea name="content"></textarea>',
					'<a href="javascript:;" class="btn-red check-login fr" id="reply-message-form-submit">回复</a>',
					'<div class="clear"></div>',
				'</form>',
			'</div>'].join('');
			$(this).parent().append(html).find('.reply-container').slideDown();
		});
		$(document).on('click', '.reply-child-link', function(){
			$('.reply-container').slideUp('normal', function(){
				$(this).remove();
			});
			var html = ['<div class="reply-container hide">',
				'<form id="reply-message-form">',
					'<input type="hidden" name="target" value="', home.user_id, '" />',
					'<input type="hidden" name="parent" value="', $(this).attr("data-parent"), '" />',
					'<textarea name="content"></textarea>',
					'<a href="javascript:;" class="btn-red check-login fr" id="reply-message-form-submit">回复</a>',
					'<div class="clear"></div>',
				'</form>',
			'</div>'].join('');
			$(this).parent().parent().parent().append(html).find('.reply-container').slideDown();
		});
		
		//提交回复
		$(document).on('submit', '#reply-message-form', function(){
			home.submitReplyMessageForm();
			return false;
		});
	},
	'init':function(){
		this.loadScripts();
		this.events();
	}
};