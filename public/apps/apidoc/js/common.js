var common = {
	'menu': function(){
		$('#main-menu').on('click', '.has-sub > a', function(){
			//非顶级菜单，或非缩起状态，或者屏幕很小（本来是大的，菜单缩起后变小）,或者IE8（因为IE8下折叠没效果）
			if($(this).parent().parent().parent().hasClass('has-sub') || !$('#sidebar-menu').hasClass('collapsed') || $(window).width() < 768 || ($.browser.msie && $.browser.version == '8.0')){
				var slideElapse = 300;//滑动效果持续
				$li = $(this).parent();//父级li
				$ul = $(this).next('ul');//子菜单的ul
				$_li = $ul.children('li');//子菜单的li
				if($li.hasClass('expanded')){
					//关闭
					$ul.slideUp(slideElapse, function(){
						$li.removeClass('expanded').removeClass('opened');
						$(this).removeAttr('style');
					});
				}else{
					//打开
					$ul.slideDown(slideElapse, function(){
						$(this).removeAttr('style');
					});
					$li.addClass('expanded');
					$_li.addClass('is-hidden');
					setTimeout((function($li){
						return function(){
							$li.addClass('is-shown');
						}
					})($_li), 0);
					setTimeout((function($li){
						return function(){
							$li.removeClass('is-hidden is-shown');
						}
					})($_li), 500);
					
					//关闭其它打开的同辈元素
					$li.siblings('.expanded').children('ul').slideUp(slideElapse, function(){
						$li.siblings('.expanded').removeClass('expanded').removeClass('opened');
						$(this).removeAttr('style');
					});
				}
				return false;
			}
		});
		
		//小屏幕下打开关闭上面的菜单
		$('.toggle-mobile-menu').on('click', function(){
			$('#main-menu').toggleClass('mobile-is-visible');
		});
		
		//打开缩起左侧菜单
		$('.toggle-sidebar').on('click', function(){
			$('#sidebar-menu').toggleClass('collapsed');
			$(window).resize();
			$.ajax({
				type: 'POST',
				url: system.url('admin/system/setting'),
				data: {
					'_key': 'admin_sidebar_class',
					'class':$('#sidebar-menu').hasClass('collapsed') ? 'collapsed' : ''
				}
			});
		});
		
		//左侧菜单固定
		if($('.sidebar-menu').hasClass('fixed') && !($.browser.msie && $.browser.version < 9)){
			//插件不支持IE8
			system.getCss(system.assets('css/perfect-scrollbar.css'), function(){
				system.getScript(system.assets('js/perfect-scrollbar.js'), function(){
					if(parseInt($(window).width()) > 768 && !$('.sidebar-menu').hasClass('collapsed')){
						$('.sidebar-menu-inner').perfectScrollbar({
							'wheelPropagation': true
						});
					}
					$(window).resize(function(){
						if(parseInt($(window).width()) > 768 && !$('.sidebar-menu').hasClass('collapsed')){
							$('.sidebar-menu-inner').perfectScrollbar({
								'wheelPropagation': true
							});
						}else{
							$('.sidebar-menu-inner').perfectScrollbar('destroy');
						}
					});
				});
			});
		}else{
			//ie8的情况下移除fixed
			$('.sidebar-menu').removeClass('fixed');
		}
	},
	'fixContent': function(){
		//下拉后顶部固定
		if($('.fixed-content').length){
			system.getScript(system.assets('faycms/js/fayfox.fixcontent.js'), function(){
				$('.fixed-content').fixcontent();
			});
		}
	},
	'events': function(){
		//鼠标划过添加hover
		$(document).on('mouseenter', '.box-title, .toggle-hover, .list-table tr', function(){
			$(this).addClass('hover');
		}).on('mouseleave', '.box-title, .toggle-hover, .list-table tr', function (){
			$(this).removeClass('hover');
		});
		//打开关闭box
		$(document).on('click', '.box-title .toggle', function(){
			$(this).parent().parent().toggleClass('closed');
		});
		//表格间隔色
		$('.list-table').each(function(){
			$(this).find('tr:even').addClass('alternate');
		});
	},
	'tab': function(){
		$(document).on('click', '.tabbable .nav-tabs a', function(){
			if(!$(this).parent().hasClass('active')){
				//如果被点击的tab不是当前tab，则先把当前tab对应div中的poshytip清掉
				$($(this).parent().siblings('.active').find('a').attr('href')).find('input,select,textarea').each(function(){
					$(this).poshytip('hide');
				});
			}
			$($(this).attr('href')).show().siblings().hide();
			$(this).parent().addClass('active').siblings().removeClass('active');
			
			return false;
		});
		
		var hash = window.location.hash;
		$('.tabbable').each(function(){
			//url中有指定锚点，且本实例中包含此锚点，打开指定锚点的tab
			if($(this).find('.nav-tabs a[href="'+hash+'"]').length){
				$(this).find('.nav-tabs a[href="'+hash+'"]').click();
			}else if($(this).find('.nav-tabs li.active').length){
				//有指定打开那个tab的class，打开对应的tab
				$(this).find('.nav-tabs li.active a').click();
			}else{
				//默认打开第一个tab
				$(this).find('.nav-tabs li:first a').click();
			}
		});
	},
	'textAutosize': function(){
		if($('textarea.autosize').length){
			system.getScript(system.assets('js/autosize.min.js'), function(){
				autosize($('textarea.autosize'));
			});
		}
	},
	'prettyPrint': function(){
		if($('.prettyprint').length){
			system.getScript(system.assets('js/prettify.js'), function(){
				prettyPrint();
			});
		}
	},
	'notify': function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			message = '<p>' + message + '</p>';
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'timeOut': 5000,
					'closeButton': false,
					'click': 'fadeOut',
					'positionClass': 'toast-top-right'
				});
			}else if(type == 'error'){
				//报错，也在右上角，显示关闭按钮，不消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-top-right'
				});
			}else if(type == 'alert'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': 'fadeOut'
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-right'
				});
			}
		});
	},
	'alert': function(message){
		this.notify(message, 'alert');
	},
	'json': {
		'replacer': function(match, pIndent, pKey, pVal, pEnd) {
			var key = '<span class=json-key>';
			var val = '<span class=json-number>';
			var str = '<span class=json-string>';
			var r = pIndent || '';
			if (pKey)
				r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
			if (pVal)
				r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
			return r + (pEnd || '');
		},
		'prettyPrint': function(obj) {
			var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
			return JSON.stringify(obj, null, 4)
				.replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
				.replace(/</g, '&lt;').replace(/>/g, '&gt;')
				.replace(jsonLine, common.json.replacer);
		}
	},
	'jsonView': function(){
		$('.jsonview').each(function(){
			try{
				var jsonObj = $.parseJSON($(this).text());
			}catch(e){
				jsonObj = false;
			}
			if(jsonObj){
				if($.browser.msie && $.browser.version < 9){
					system.getScript(system.assets('js/json2.js'), function(){
						$(this).html(common.json.prettyPrint(jsonObj));
					});
				}else{
					$(this).html(common.json.prettyPrint(jsonObj));
				}
			}
		});
	},
	'init': function(){
		this.menu();
		this.events();

		this.fixContent();
		this.tab();
		this.textAutosize();
		this.prettyPrint();
		this.jsonView();
	}
};