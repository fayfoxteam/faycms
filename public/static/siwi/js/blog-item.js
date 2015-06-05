var blog_item = {
	'loadScripts':function(){
		//遮蔽层
		system.getScript(system.url('js/custom/fayfox.block.js'));
		//滚动
		system.getScript(system.url('js/jquery.scrollTo-min.js'));
		//分享插件
		system.getScript('http://v3.jiathis.com/code/jia.js');
	},
	//ajax提交评论表单
	'submitCreateCommentForm':function(){
		$('.create-comment').block();
		$.ajax({
			'type': 'POST',
			'url': system.url('user/comment/create'),
			'data': $('#create-comment-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('.create-comment').unblock();
				if(resp.status){
					$(".create-comment [name='content']").val('');
					var html = ['<li>',
						'<div class="avatar">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html">',
								'<img src="', system.url('file/pic', {
									't':2,
									's':'avatar',
									'f':resp.data.avatar,
								}), '" />',
							'</a>',
						'</div>',
						'<div class="meta">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html" class="user-link">',
								resp.data.nickname,
							'</a>',
							'<time class="time">', resp.data.date, '</time>',
						'</div>',
						'<div class="comment-content">', system.encode(resp.data.content), '</div>',
						'<a href="javascript:;" title="回复" class="icon-reply reply-link" data-parent="', resp.data.id, '"></a>',
					'</li>'].join('');
					$(".comment-container ul").prepend(html);
				}else{
					common.showError(resp.message);
				}
			}
		});
		return false;
	},
	//ajax提交回复表单
	'submitReplyCommentForm':function(){
		$(".reply-container").block();
		$.ajax({
			'type': 'POST',
			'url': system.url('user/comment/create'),
			'data': $('#reply-comment-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('.reply-container').unblock();
				if(resp.status){
					var html = ['<li>',
						'<div class="avatar">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html">',
								'<img src="', system.url('file/pic', {
									't':2,
									's':'avatar',
									'f':resp.data.avatar,
								}), '" />',
							'</a>',
						'</div>',
						'<div class="meta">',
							'<a href="', system.url('u/'+resp.data.user_id), '.html" class="user-link">',
								resp.data.nickname,
							'</a>',
							'<time class="time">', resp.data.date, '</time>',
						'</div>',
						'<div class="comment-content">', system.encode(resp.data.content), '</div>',
						'<div class="parent">',
							'<em class="arrow-border"></em>',
							'<em class="arrow"></em>',
							'<a href="', system.url('u/'+resp.data.parent_user_id), '.html" class="parent-user-link">',
								resp.data.parent_nickname,
							'</a> 说：',
							'<p class="parent-content">', system.encode(resp.data.parent_content), '</p>',
						'</div>',
						'<a href="javascript:;" title="回复" class="icon-reply reply-link" data-parent="', resp.data.id, '"></a>',
					'</li>'].join('');
					$(".comment-container ul").prepend(html);
					$(".reply-container").slideUp('normal', function(){
						$(this).remove();
					});
					$.scrollTo('.comment-container', 500);
				}else{
					common.showError(resp.message);
				}
			}
		});
		return false;
	},
	'pager':function(){
		$(".comment-container").on('click', 'a.page-numbers', function(){
			$(".comment-container").block();
			$.ajax({
				'type': "GET",
				'url': $(this).attr("href"),
				'cache': false,
				'success': function(resp){
					$(".comment-container").html($(resp).find('.comment-container').html());
					$(".comment-container").unblock();
					$.scrollTo('.comment-container', 500);
				}
			});
			return false;
		});
	},
	'addFavorite':function(post_id){
		$.ajax({
			'type': 'GET',
			'url': system.url('user/favourite/add'),
			'data': {
				'id':post_id
			},
			'cache': false,
			'dataType': 'json',
			'success': function(resp){
				if(resp.status){
					$('.favourite-link').addClass('favored');
					common.showSuccess('收藏成功');
				}else{
					if(resp.error_code == 'already-favored'){
						$('.favourite-link').addClass('favored');
					}
					common.showError(resp.message);
				}
			}
		});
	},
	'removeFavorite':function(post_id){
		$.ajax({
			'type': 'GET',
			'url': system.url('user/favourite/remove'),
			'data': {
				'id':post_id
			},
			'cache': false,
			'dataType': 'json',
			'success': function(resp){
				if(resp.status){
					$('.favourite-link').removeClass('favored');
					common.showSuccess('取消收藏成功');
				}else{
					if(resp.error_code == 'unfavored'){
						$('.favourite-link').removeClass('favored');
					}
					common.showError(resp.message);
				}
			}
		});
	},
	'addLike':function(post_id){
		$.ajax({
			'type': 'GET',
			'url': system.url('user/like/add'),
			'data': {
				'id':post_id
			},
			'cache': false,
			'dataType': 'json',
			'success': function(resp){
				if(resp.status){
					$('.like-link').addClass('liked');
					common.showSuccess('赞+1');
				}else{
					if(resp.error_code == 'already-liked'){
						$('.like-link').addClass('liked');
					}
					common.showError(resp.message);
				}
			}
		});
	},
	'removeLike':function(post_id){
		$.ajax({
			'type': 'GET',
			'url': system.url('user/like/remove'),
			'data': {
				'id':post_id
			},
			'cache': false,
			'dataType': 'json',
			'success': function(resp){
				if(resp.status){
					$('.like-link').removeClass('liked');
					common.showSuccess('取消赞');
				}else{
					if(resp.error_code == 'liked'){
						$('.like-link').removeClass('liked');
					}
					common.showError(resp.message);
				}
			}
		});
	},
	'events':function(){
		//提交评论
		$(document).on('submit', '#create-comment-form', function(){
			blog_item.submitCreateCommentForm();
			return false;
		});
		
		//给出回复界面
		$(document).on('click', '.reply-link', function(){
			$('.reply-container').slideUp('normal', function(){
				$(this).remove();
			});
			if(!$(this).parent().find('.reply-container').length){
				var html = ['<div class="reply-container hide">',
					'<form id="reply-comment-form">',
						'<input type="hidden" name="target" value="', blog_item.id, '" />',
						'<input type="hidden" name="parent" value="', $(this).attr("data-parent"), '" />',
						'<textarea name="content"></textarea>',
						'<a href="javascript:;" class="btn-red check-login fr" id="reply-comment-form-submit">回复</a>',
						'<div class="clear"></div>',
					'</form>',
				'</div>'].join('');
				$(this).parent().append(html).find('.reply-container').slideDown();
			}
		});
		
		//提交回复
		$(document).on('submit', '#reply-comment-form', function(){
			blog_item.submitReplyCommentForm();
			return false;
		});
		
		//点赞
		$(document).on('click', '.like-link', function(){
			if($(this).hasClass('liked')){
				blog_item.removeLike($(this).attr('data-id'));
			}else{
				blog_item.addLike($(this).attr('data-id'));
			}
		});
		
		//收藏
		$(document).on('click', '.favourite-link', function(){
			if($(this).hasClass('favored')){
				blog_item.removeFavorite($(this).attr('data-id'));
			}else{
				blog_item.addFavorite($(this).attr('data-id'));
			}
		});
	},
	'init':function(){
		this.loadScripts();
		this.events();
		this.pager();
	}
};