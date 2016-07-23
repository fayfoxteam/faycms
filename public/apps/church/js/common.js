var common = {
	/**
	 * 顶部主导航
	 */
	'mainMenu': function(){
		system.getScript(system.assets('js/superfish.js'), function(){
			$('.main-menu').superfish({
				delay: 300,
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
	'mobileMenu': function(){
		var $mobilePageHeader = $('.mobile-page-header');
		//打开菜单
		$('.toggle-mobile-menu').on('click', function(){
			$mobilePageHeader.find('.mask').addClass('show');
			$mobilePageHeader.find('.mobile-menu-container').addClass('open');
		});
		//点击蒙板关闭菜单
		$mobilePageHeader.find('.mask').on('click', function(){
			$(this).removeClass('show');
			$mobilePageHeader.find('.mobile-menu-container').removeClass('open');
		});
		//开关子菜单
		var $mobileMenu = $('.mobile-menu');
		$mobileMenu.find('a').on('click', function(){
			var $parentLi = $(this).parent();
			if($parentLi.hasClass('opened')){
				//本来处于打开状态，现在关闭掉
				$parentLi.removeClass('opened').children('ul').slideUp();
			}else{
				//本来出于关闭状态，现在打开
				$parentLi.addClass('opened').children('ul').slideDown();
				$parentLi.siblings('li').removeClass('opened').children('ul').slideUp();
			}
			
			if($parentLi.hasClass('has-children')){
				//若非叶子节点，不跳转
				return false;
			}
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
	 * 当页面往下滚动时，顶部导航固定
	 */
	'fixHeader': function(scrollOffset){
		var $body = $('body');
		if(scrollOffset > 70){
			$body.addClass('header-fixed');
			$('.page-header').addClass('animated fadeInDown');
		}else{
			$body.removeClass('header-fixed');
			$('.page-header').removeClass('animated fadeInDown');
		}
	},
	/**
	 * 集中调用滚动时需要触发的函数
	 */
	'scrollActions': function(){
		var scrollOffset = $(window).scrollTop();
		this.showScrollToTopButton(scrollOffset);
		this.fixHeader(scrollOffset);
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
		this.mobileMenu();
		this.scrollToTop();
		this.scrollEvents();
		this.swiper();
	}
};
