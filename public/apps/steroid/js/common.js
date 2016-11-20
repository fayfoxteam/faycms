var common = {
	'toast':function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else if(type == 'error'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-middle'
				});
			}
		});
	},
	'form':function(){
		//表单提交
		$(document).on('click', 'a[id$="submit"]', function(){
			$('form#'+$(this).attr('id').replace('-submit', '')).submit();
			return false;
		});
	},
	'scrollTo': function(){
		system.getScript(system.assets('js/jquery.scrollTo.min.js'), function(){
			$('a[href^="#"]').on('click', function(){
				var href = $(this).attr('href');
				if(href == '#'){
					$.scrollTo(0, 500, {
						offset:-60
					});
				}else{
					$.scrollTo($(this).attr('href'), 500, {
						offset:-60
					});
				}
				
				return false;
			});
		});
	},
	'animate': function(){
		system.getScript(system.assets('faycms/js/fayfox.scroll.js'), function(){
			//顶部固定菜单
			$('#section-banner').scrollOut(function(){
				$('.page-header-fixed').addClass('animated fadeInDown');
			}).scrollIn(function(){
				$('.page-header-fixed').removeClass('animated fadeInDown');
			});
			
			//商品列表延迟动画
			$('#section-products').scrollIn(function(o){
				$(o).addClass('animated fadeIn');
			}, 200);
			
			//ancillary列表项滑入
			$('.ancillary-list article').scrollIn(function(o){
				$(o).find('.col-md-4').addClass('animated fadeInRight');
				$(o).find('.col-md-8').addClass('animated fadeInLeft');
			});
			
			//faq
			$('#section-faq').scrollIn(function(o){
				$(o).find('.title-group, .question-list').addClass('animated fadeInUp');
			});
			
			$('#section-blog').find('article').scrollIn(function(o){
				$(o).addClass('animated pulse');
			});
			
			//contact
			$('#contact-form').scrollIn(function(o){
				$(o).addClass('animated fadeInLeft');
			});
			$('#baidu-map-baidu-map').scrollIn(function(o){
				$(o).addClass('animated fadeInUp');
			});
			$('#section-contact .contact-info').scrollIn(function(o){
				$(o).addClass('animated fadeInRight');
			});
		});
	},
	'init': function(){
		this.form();
		this.animate();
		this.scrollTo();
	}
};
