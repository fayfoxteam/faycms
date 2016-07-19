var common = {
	/**
	 * 顶部主导航
	 */
	'mainMenu': function(){
		system.getScript(system.assets('js/superfish.js'), function(){
			$('.main-menu').superfish({
				delay: 500,
				onBeforeShow: function(ul) {
					$(this).removeClass('animated fast fadeOutDown');
					$(this).addClass('animated fast fadeIn');
				},
				onBeforeHide: function(ul) {
					$(this).removeClass('animated fast fadeIn');
					$(this).addClass('animated fast fadeOutDown');
				}
			});
		});
	},
	/**
	 * 缓慢滚到顶部
	 */
	'scrollToTop': function(){
		$('.scroll-to-top').on('click', function(e){
			"use strict";
			$('html,body').animate({
				scrollTop: 0
			}, 'slow');
			e.preventDefault();
			return false;
		});
	},
	/**
	 * 显示“回到顶部”按钮
	 */
	'showScrollToTopButton': function(scrollOffset){
		var $scrollToTop = jQuery('.scroll-to-top');
		if(scrollOffset > 70){
			$scrollToTop.addClass('show');
		}else{
			$scrollToTop.removeClass('show');
		}
	},
	/**
	 * 集中调用滚动时需要触发的函数
	 */
	'scrollActions': function(){
		var scrollOffset = $(window).scrollTop();
		this.showScrollToTopButton(scrollOffset);
	},
	/**
	 * 网页滚动和调整大小时，触发注册的滚动事件
	 */
	'scrollEvents': function(){
		$(window).resize(function(){
			common.scrollActions();
		});
		$(window).scroll(function(){
			common.scrollActions();
		});
	},
	'swiper': function(){
		if($('.swiper-container').length){
			system.getCss(system.assets('js/swiper/css/swiper.min.css'));
			system.getScript(system.assets('js/swiper/js/swiper.jquery.min.js'), function(){
				var swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
					grabCursor: true,
					loop: true,
					nextButton: '.swiper-btn-next',
					prevButton: '.swiper-btn-prev'
				});
			});
		}
	},
	'init': function(){
		this.mainMenu();
		this.scrollToTop();
		this.scrollEvents();
		this.swiper();
	}
};
