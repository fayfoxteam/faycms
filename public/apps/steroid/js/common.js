var common = {
	'scrollTo': function(){
		system.getScript(system.assets('js/jquery.scrollTo.min.js'), function(){
			$(document).on('click', 'a[href^="#"]', function(){
				var href = $(this).attr('href');
				var offset = $(window).width() > 768 ? -60 : 0;
				if(href == '#'){
					$.scrollTo(0, 500, {
						offset:0
					});
				}else{
					$.scrollTo($(this).attr('href'), 500, {
						offset:offset
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
			
			//blog
			$('#section-blog').find('article').scrollIn(function(o){
				$(o).addClass('animated pulse');
			});
			
			//contact
			$('.contact-form').scrollIn(function(o){
				$(o).addClass('animated fadeInLeft');
			});
			$('#baidu-map-baidu-map').scrollIn(function(o){
				$(o).addClass('animated fadeInUp');
			});
			$('#section-contact .contact-info').scrollIn(function(o){
				$(o).addClass('animated fadeInRight');
			});
			
			$('#section-product-link').scrollIn(function(o){
				$(o).find('.col-md-7,.col-md-5').addClass('animated fadeInUp');
			});
		});
	},
	'mobileNav': function(){
		var $mobileNav = $('#mobile-nav');
		$mobileNav.on('click', '.mobile-bar-container a', function(){
			//点击开关按钮
			$mobileNav.toggleClass('open');
		}).on('click', '.navigator a', function(){
			//点击菜单项
			$mobileNav.removeClass('open');
		}).on('click', '.mobile-nav-mask', function(){
			//点击蒙板
			$mobileNav.removeClass('open');
		});
	},
	'init': function(){
		this.animate();
		this.mobileNav();
		this.scrollTo();
	}
};
