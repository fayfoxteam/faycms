/**
 * tags输入
 */
jQuery.fn.extend({
	'ftextext': function(options){
		if(typeof(options) == 'undefined'){
			options = {};
		}
		var settings = {
			'maxHeight':200
		};
		$.each(options, function(i, n){
			settings[i] = n;
		});
		var width = $(this).width();
		var padding_left = $(this).css('padding-left');
		var padding_right = $(this).css('padding-right');
		
		var app = {
			'obj':$(this),//被隐藏掉到原始输入框
			'container':null,
			'drop':null,
			'lastText':null,
			'input':null,//模拟出来到可见输入框
			'cache':{},//ajax缓存
			'width':(parseInt(width) + parseInt(padding_left) + parseInt(padding_right)) + 'px',
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
			'arrayUnique':function(inputArr){
				var key = '',
				tmp_arr2 = {},
				val = '';

				var __array_search = function(needle, haystack){
					var fkey = '';
					for(fkey in haystack){$("#f_autocomplete")
						if(haystack.hasOwnProperty(fkey)){
							if((haystack[fkey] + '') === (needle + '')){
								return fkey;
							}
						}
					}
					return false;
				};

				for(key in inputArr){
					if(inputArr.hasOwnProperty(key)){
						val = inputArr[key];
						if (false === __array_search(val, tmp_arr2)){
							tmp_arr2[key] = val;
						}
					}
				}

				return tmp_arr2;
			},
			'setup':function(){//初始化html结构
				app.obj.hide();
				app.obj.after([
					'<div class="ftextext-container" style="width:', app.width, ';">',
						'<ul class="ftextext-choices">',
							'<li class="ftextext-input">',
								'<input type="text" autocomplete="off" style="width:25px;" />',
							'</li>',
						'</ul>',
						'<div class="ftextext-drop" style="width:', app.width, ';">',
							'<ul class="ftextext-results"></ul>',
						'</div>',
					'</div>'
				].join(''));
				app.container = app.obj.next('.ftextext-container');
				app.drop = app.container.find('.ftextext-drop');
				app.input = app.container.find('input');
				app.setInputText(app.obj.val());
				app.setChoice();
			},
			'getChoices':function(key){//ajax获取列表页
				if(typeof(key) == 'undefined'){
					key = '';
				}
				app.lastText = key;
				if(typeof(app.cache[key]) != 'undefined'){
					app.showResults(app.cache[key]);
				}else{
					clearTimeout(app.lazyAjax);
					app.lazyAjax = setTimeout(function(){
						$.ajax({
							type: "GET",
							url: settings.url,
							dataType: "json",
							data: {"key":key},
							success: function(resp){
								if(resp.status){
									app.cache[key] = resp.data;
									if(resp.data.length){
										app.showResults(resp.data);
									}else{
										app.drop.hide();
									}
								}else{
									alert(resp.message);
								}
							}
						});
					}, 300);
				}
			},
			'showResults':function(data){
				if(typeof(data) != 'undefined'){
					//更新数据
					app.container.find('.ftextext-results').html('');
					var choices = app.obj.val().split(',');
					$.each(data, function(i, n){
						if(app.lastText){
							var text = app.encode(n.title).replace(eval('/'+app.lastText.replace(/\//g, '\\/')+'/g'), '<span class="highlight">'+app.lastText+'</span>');
						}else{
							var text = app.encode(n.title);
						}
						if($.inArray(n.title, choices) == -1){
							app.container.find('.ftextext-results').append('<li>'+text+'</li>');
						}else{
							app.container.find('.ftextext-results').append('<li class="disabled">'+text+'</li>');
						}
					});
				}
				//判断是否要显示
				var isShow = false;
				app.container.find('.ftextext-results li').each(function(){
					if(!$(this).hasClass('disabled')){
						isShow = true;
					}
				});
				if(isShow){
					app.drop.show();
					//最大高度限制
					if(parseInt(app.container.find('.ftextext-drop ul').height()) > parseInt(settings.maxHeight)){
						app.drop.css({
							"height":settings.maxHeight + 1,
							"overflow":"auto"
						});
					}else{
						app.drop.css({
							"height":"auto"
						});
					}
				}else{
					app.drop.hide();
				}
			},
			'setChoice':function(){//设置一个选项
				var text = app.input.val();
				if(text == '')return false;
				//可一次性输入多个
				var text_arr = text.split(',');
				//去空格
				$.each(text_arr, function(i, n){
					text_arr[i] = $.trim(n);
				});
				text_arr = app.arrayUnique(text_arr);//去重
				
				var choices = [];//可见到已选择到选项
				app.container.find('.ftextext-choices li').each(function(){
					choices.push($(this).text());
				});
				
				//设置选项
				$.each(text_arr, function(i, n){
					if($.inArray(n, choices) == -1){
						app.container.find('.ftextext-choices li.ftextext-input').before([
	     					'<li class="ftextext-choice">',
	     						'<span>', app.encode(n), '</span>',
	     						'<a href="javascript:;" class="ftextext-remove"></a>',
	     					'</li>',
	     				].join(''));
					}
				});
				
				//设置隐藏着到输入框
				app.refreshChoices();
				
				app.input.val('').css('width', '25');
			},
			'refreshChoices':function(){//根据可见到模拟元素，重置隐藏着的真实输入框
				var choices_arr = [];
				app.container.find('.ftextext-choices li').each(function(){
					var text = $.trim($(this).text());
					if(text){
						choices_arr.push(text);
					}
				});
				app.obj.val(choices_arr.join(','));
			},
			'setInputText':function(str){//设置可见到输入框
				app.input.val(str);
				var width = 14 * parseInt(app.input.val().length);
				if(width > 25){
					if(width > (app.width - 20)){
						width = app.width - 20;
					}
					app.input.css('width', width);
				}
			},
			'scrollTo':function(active){//上下键操作时，防止列表滚到外面去
				if(app.drop.find('li').length > 2){
					var itemHeight = parseInt(app.drop.find('li:visible:eq(1)').offset().top - app.drop.find('li:visible:eq(0)').offset().top);
					var active_offset_top = parseInt(active.offset().top);
					var drop_offset_top = parseInt(app.drop.offset().top);
					if(active_offset_top - drop_offset_top < 0){
						app.drop.scrollTop(app.drop.scrollTop() - (drop_offset_top - active_offset_top));
					}
					if(active_offset_top - drop_offset_top + itemHeight > settings.maxHeight){
						app.drop.scrollTop(app.drop.scrollTop() + (active_offset_top - drop_offset_top - settings.maxHeight + itemHeight));
					}
				}
			},
			'goDown':function(){
				var current = app.drop.find('li.active');
				app.drop.show();
				var active = {};
				if(current.length > 0){
					active = current.nextAll(':visible:first');
				}else{
					active = app.drop.find('li:first');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.setInputText(active.text());
					
					app.scrollTo(active);
				}
			},
			'goUp':function(current){
				var current = app.drop.find('li.active');
				var active = {};
				if(current.length > 0){
					active = current.prevAll(':visible:first');
				}else{
					active = app.drop.find('li:last');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.setInputText(active.text());
					
					app.scrollTo(active);
				}else{
					app.setInputText('');
					app.drop.scrollTop(0);
					app.drop.find('li').removeClass('active');
					app.drop.hide();
				}
			},
			'events':function(){
				//container任意位置点击
				app.container.on('click', '.ftextext-choices', function(){
					$(this).find('input').focus();
				});
				
				//输入框得到焦点
				app.container.on('focus', 'input', function(){
					app.container.addClass('ftextext-active');
					if($(this).val() !== app.lastText){
						app.lastText = $(this).val();
						app.getChoices(app.lastText);
					}else{
						app.showResults();
					}
				});
				
				//输入框失去焦点
				app.container.on('blur', 'input', function(){
					app.container.removeClass('ftextext-active');
					app.drop.fadeOut('fast');
					app.setChoice();
				});
				
				//鼠标移入添加class
				app.container.on('hover', '.ftextext-drop li', function(){
					$(this).addClass('active').siblings().removeClass('active');
				});
				
				//方向键
				app.container.on('keydown', 'input', function(event){
					if(event.keyCode == 38){//向上
						if(!app.drop.is(':hidden')){
							app.goUp();
						}
					}else if(event.keyCode == 40){//向下
						app.goDown();
					}
				});
				
				//输入框有输入
				app.container.on('keyup', 'input', function(event){
					var current = app.container.find('.ftextext-results li.active');
					if(event.keyCode == 38){//向上
						//app.goUp(current);
					}else if(event.keyCode == 40){//向下
						//app.goDown(current);
					}else if(event.keyCode == 13 || event.keyCode == 108){//回车
						if(current.length > 0){
							app.setInputText(current.text());
						}
						app.setChoice();
						app.drop.hide();
						return;
					}else{
						app.getChoices(app.input.val());
						var width = 14 * parseInt(app.input.val().length);
						if(width > 25){
							if(width > (app.width - 20)){
								width = app.width - 20;
							}
							app.input.css('width', width);
						}
					}
				});

				//点击选择
				app.container.on('click', '.ftextext-results li', function(){
					app.setInputText($(this).text());
					app.setChoice();
					$(this).addClass('disabled');
				});
				
				//删除选项
				app.container.on('click', '.ftextext-remove', function(){
					var text = $(this).prev('span').text();
					//下拉补全中若有此项，使其可选
					app.container.find('.ftextext-results li').each(function(){
						if($(this).text() == text){
							$(this).removeClass('disabled');
						}
					});
					$(this).parent().remove();
					app.refreshChoices();
					return false;
				});
			},
			'init':function(){
				this.setup();
				this.events();
			}
		};
		
		app.init();
		
	}
});