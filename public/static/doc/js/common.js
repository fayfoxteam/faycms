var common = {
	//左侧导航
	'mainMenu':function(){
		//点击开关
		$('#main-menu').on('click', '.has-sub > a', function(){
			var slideElapse = 300;//滑动效果持续
			$li = $(this).parent();//父级li
			$ul = $(this).next('ul');//子菜单的ul
			$_li = $ul.children('li');//子菜单的li
			if($li.hasClass('expanded')){
				//关闭
				$ul.slideUp(slideElapse);
				$li.removeClass('expanded');
			}else{
				//打开
				$ul.slideDown(slideElapse);
				$li.addClass('expanded');
				$_li.addClass('is-hidden');
				setTimeout((function($li){
					return function(){
						console.log($li);
						$li.addClass('is-shown');
					}
				})($_li), 0);
				setTimeout((function($li){
					return function(){
						$li.removeClass('is-hidden is-shown');
					}
				})($_li), 500);
				
				//关闭其它打开的同辈元素
				$li.siblings('.expanded').removeClass('expanded').children('ul').slideUp(slideElapse);
			}
			return false;
		});
		
		$('.toggle-mobile-menu').on('click', function(){
			$('#main-menu').toggleClass('mobile-is-visible');
		});
		
		//自定义滚动条
		system.getScript(system.url('js/jquery.slimscroll.min.js'), function(){
//			$('.sidebar-menu-inner').slimScroll({
//				'height':$('html').height(),
//				'width':$('.sidebar-menu').width(),
//				'color':'#a1b2bd',
//				'opacity':.3
//			});
//			$(window).resize(function(){
//				$('.sidebar-menu-inner').slimScroll({'destroy':1});
//				$('.sidebar-menu-inner').slimScroll({
//					'height':$('html').height(),
//					'width':$('.sidebar-menu').width(),
//					'color':'#a1b2bd',
//					'opacity':.3
//				});
//			});
		});
	},
	'prettyPrint':function(){
		system.getScript(system.url('js/prettify.js'), function(){
			prettyPrint();
		});
	},
	'init':function(){
		this.mainMenu();
		this.prettyPrint();
	}
};