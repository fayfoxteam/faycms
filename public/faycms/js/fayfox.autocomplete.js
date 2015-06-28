jQuery.fn.extend({
	autocomplete: function(options){
		options = options || {};
		var settings = {
			'maxHeight':200,
			'startSuggestLength':3,
			'onSelect':function(obj){}//选中某项后执行
		};
		$.each(options, function(i, n){
			settings[i] = n;
		});
		var offset = $(this).offset();
		var width = $(this).width();
		var height = $(this).height();
		var padding_top = $(this).css('padding-top');
		var padding_bottom = $(this).css('padding-bottom');
		var padding_left = $(this).css('padding-left');
		var padding_right = $(this).css('padding-right');
		var border_top = $(this).css('border-top-width');
		var border_bottom = $(this).css('border-bottom-width');
		
		var app = {
			'obj':$(this),
			'lastText':null,
			'container':null,
			'cache':{},//ajax缓存
			'width':(parseInt(width) + parseInt(padding_left) + parseInt(padding_right)) + 'px',
			'top':(parseInt(offset.top) + parseInt(height) + parseInt(padding_top) + parseInt(padding_bottom) + parseInt(border_top) + parseInt(border_bottom) + 2) + 'px',
			'left':offset.left + 'px',
			'lazyAjax':null,//用户连续键盘输入时，不发送ajax
			'encode' : function(str){
				var s = "";
				if (str == undefined || str.length == 0) return "";
				s = str.replace(/&/g, "&amp;");
				s = s.replace(/</g, "&lt;");
				s = s.replace(/>/g, "&gt;");
				s = s.replace(/ /g,"&nbsp;");
				s = s.replace(/\'/g, "&#39;");
				s = s.replace(/\"/g, "&quot;");
				s = s.replace(/\n/g, "<br>");
				return s;
			},
			'getSuggests':function(key){
				if(typeof(key) == 'undefined'){
					key = '';
				}
				app.lastText = key;
				if(typeof(app.cache[key]) != 'undefined'){
					app.showSuggests(app.cache[key]);
				}else{
					clearTimeout(app.lazyAjax);
					app.lazyAjax = setTimeout(function(){
						$.ajax({
							type: 'GET',
							url: settings.url,
							dataType: 'json',
							data: {'key':key},
							success: function(resp){
								if(resp.status){
									app.cache[key] = resp.data;
									if(resp.data.length){
										app.showSuggests(resp.data);
									}else{
										app.container.find('ul').html('');
										app.container.hide();
									}
								}else{
									alert(resp.message);
								}
							}
						});
					}, 300);
				}
			},
			'showSuggests':function(data){
				if(typeof(data) != 'undefined'){
					app.container.find('ul').html('');
					$.each(data, function(i, n){
						if(app.lastText){
							var text = app.encode(n.title).replace(eval('/'+app.lastText.replace(/\//g, '\\/')+'/g'), '<span class="highlight">'+app.lastText+'</span>');
						}else{
							var text = app.encode(n.title);
						}
						app.container.find('ul').append('<li>'+text+'</li>');
					});
				}
				if(app.container.find('li').length > 0){
					app.container.show();
					if(parseInt(app.container.find('ul').height()) > parseInt(settings.maxHeight)){
						app.container.css({
							"height":settings.maxHeight + 1,
							"overflow":"auto"
						});
					}else{
						app.container.css({
							"height":"auto"
						});
					}
				}else{
					app.container.hide();
				}
			},
			'scrollTo':function(active){//上下键操作时，防止列表滚到外面去
				if(app.container.find('li').length > 2){
					var itemHeight = parseInt(app.container.find('li:visible:eq(1)').offset().top - app.container.find('li:visible:eq(0)').offset().top);
					var active_offset_top = parseInt(active.offset().top);
					var drop_offset_top = parseInt(app.container.offset().top);
					if(active_offset_top - drop_offset_top < 0){
						app.container.scrollTop(app.container.scrollTop() - (drop_offset_top - active_offset_top));
					}
					if(active_offset_top - drop_offset_top + itemHeight > settings.maxHeight){
						app.container.scrollTop(app.container.scrollTop() + (active_offset_top - drop_offset_top - settings.maxHeight + itemHeight));
					}
				}
			},
			'goDown':function(){
				var current = app.container.find('li.active');
				app.showSuggests();
				var active = {};
				if(current.length > 0){
					active = current.nextAll(':visible:first');
				}else{
					active = app.container.find('li:first');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.obj.val(active.text());
					
					app.scrollTo(active);
				}
			},
			'goUp':function(current){
				var current = app.container.find('li.active');
				var active = {};
				if(current.length > 0){
					active = current.prevAll(':visible:first');
				}else{
					active = app.container.find('li:last');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.obj.val(active.text());
					
					app.scrollTo(active);
				}else{
					app.container.scrollTop(0);
					app.container.find('li').removeClass('active');
					app.container.hide();
				}
			},
			'events':function(){
				//输入框得到焦点
				app.obj.on('focus', function(){
					if($(this).val() !== app.lastText && $(this).val().length > settings.startSuggestLength){
						app.lastText = $(this).val();
						app.getSuggests(app.lastText);
					}else{
						app.showSuggests();
					}
				});
				
				//输入框失去焦点
				app.obj.on('blur', function(){
					app.container.fadeOut('fast');
				});
				
				//鼠标移入添加class
				app.container.on('hover', 'li', function(){
					$(this).addClass('active').siblings().removeClass('active');
				});
				
				//方向键
				app.obj.on('keydown', function(event){
					if(event.keyCode == 38){//向上
						if(!app.container.is(':hidden')){
							app.goUp();
						}
					}else if(event.keyCode == 40){//向下
						app.goDown();
					}
				});
				
				app.obj.on('keyup', function(event){
					var current = app.container.find('li.active');
					if(event.keyCode == 38){//向上
						
					}else if(event.keyCode == 40){//向下
						
					}else if(event.keyCode == 13 || event.keyCode == 108){//回车
						if(current.length > 0){
							$(this).val(current.text());
							settings.onSelect(app.obj);
						}
						app.container.fadeOut("fast");
						return;
					}else{
						if($(this).val().length >= settings.startSuggestLength){
							app.getSuggests($(this).val());
						}else{
							app.container.find('ul').html('');
							app.container.hide();
						}
					}
				});
				
				app.container.on('click', 'li', function(){
					app.obj.val($(this).text());
					settings.onSelect(app.obj);
				});
			},
			'init':function(){
				app.obj.attr("autocomplete", "off");
				$('body').append([
					'<div class="fac-container" style="width:'+app.width+';top:'+app.top+';left:'+app.left+';">',
						'<ul></ul>',
					'</div>'
				].join(''));
				app.container = $('body .fac-container:last');
				this.events();
			}
		}
		
		app.init();
	}
})