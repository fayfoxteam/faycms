/**
 * 美化下拉框
 */
jQuery.fn.extend({
	'fselect': function(options){
		var app = {
			'setup':function(node){//初始化html结构
				node.obj.hide();
				node.obj.after([
					'<div class="fselect-container ', node.obj.attr('class'), '" style="width:', node.width, ';line-height:', node.height, ';">',
						'<div class="fselect-choice">',
							'<input type="text" autocomplete="off" style="width:90%;" />',
							'<em class="fselect-arrow"></em>',
						'</div>',
						'<div class="fselect-drop">',
							'<ul class="fselect-results"></ul>',
						'</div>',
					'</div>'
				].join(''));
				node.container = $(node.obj).next('.fselect-container');
				node.drop = node.container.find('.fselect-drop');
				node.drop.width(node.container.width() + parseInt(node.container.css('padding-left')) + parseInt(node.container.css('padding-right')));
				node.drop.css('margin-left', '-' + node.container.css('padding-left'));
				node.input = node.container.find('input');
				app.setChoice(node);
			},
			'showResults':function(node){
				var inputText = node.input.val();
				node.drop.find('ul').html('');
				node.obj.find('option').each(function(){
					if(inputText){
						if($(this).text().indexOf(inputText) != -1){//符合到高亮显示
							var label = system.encode($(this).text()).replace(eval('/'+inputText.replace(/\//g, '\\/')+'/g'), '<span class="highlight">'+inputText+'</span>');
							node.drop.find('ul').append('<li data-value="'+$(this).attr('value')+'">'+label+'</li>');
						}else{//不符合的也显示
							node.drop.find('ul').append('<li data-value="'+$(this).attr('value')+'">'+system.encode($(this).text())+'</li>');
						}
					}else{//无输入不做高亮显示操作
						node.drop.find('ul').append('<li data-value="'+$(this).attr('value')+'">'+system.encode($(this).text())+'</li>');
					}
				});
				
				node.container.find('.fselect-results li[data-value="'+node.obj.val()+'"]').addClass('active');
				
				if(node.drop.find('li').length == 0){
					node.drop.find('ul').append('<li>--无可选项--</li>');
				}
				
				if(parseInt(node.drop.height()) > parseInt(node.settings.maxHeight)){
					node.drop.css({
						"height":node.settings.maxHeight + 1,
						"overflow":"auto"
					});
				}else{
					node.drop.css({
						"height":"auto"
					});
				}
				
				node.drop.show();
			},
			'setChoice':function(node, value){//设置一个选项
				if(typeof(value) != 'undefined'){
					var old_value = node.obj.val();
					if(old_value != value){
						node.obj.val(value);
						node.obj.change();
					}
				}
				
				var current_option_value = node.obj.val();
				var text = '';
				node.obj.find('option').each(function(){
					if($(this).attr('value') == current_option_value){
						text = $(this).text();
					}
				});
				node.input.val(text);
				node.settings.afterSetChoice(node.obj);
			},
			'scrollTo':function(node, active){//上下键操作时，防止列表滚到外面去
				if(node.drop.find('li').length > 2){
					var itemHeight = parseInt(node.drop.find('li:visible:eq(1)').offset().top - node.drop.find('li:visible:eq(0)').offset().top);
					var active_offset_top = parseInt(active.offset().top);
					var drop_offset_top = parseInt(node.drop.offset().top);
					if(active_offset_top - drop_offset_top < 0){
						node.drop.scrollTop(node.drop.scrollTop() - (drop_offset_top - active_offset_top));
					}
					if(active_offset_top - drop_offset_top + itemHeight > node.settings.maxHeight){
						node.drop.scrollTop(node.drop.scrollTop() + (active_offset_top - drop_offset_top - node.settings.maxHeight + itemHeight));
					}
				}
			},
			'goDown':function(node){
				var current = node.drop.find('li.active');
				node.drop.show();
				var active = {};
				if(current.length > 0){
					active = current.nextAll(':visible:first');
				}else{
					active = node.drop.find('li:first');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.setChoice(node, active.attr('data-value'));
					
					app.scrollTo(node, active);
				}
			},
			'goUp':function(node){
				var current = node.drop.find('li.active');
				var active = {};
				if(current.length > 0){
					active = current.prevAll(':visible:first');
				}else{
					active = node.drop.find('li:last');
				}
				if(active.length){
					active.addClass('active').siblings().removeClass('active');
					app.setChoice(node, active.attr('data-value'));
					
					app.scrollTo(node, active);
				}
			},
			'update':function(node){
				app.setChoice(node);
			},
			'events':function(node){
				//container任意位置点击
				node.container.on('click', '.fselect-choice', function(){
					if(node.container.hasClass('fselect-active')){
						node.container.removeClass('fselect-active');
						$(this).find('input').blur();
					}else{
						node.container.addClass('fselect-active');
						$(this).find('input').focus();
						app.showResults(node);
					}
				});
				
				//输入框失去焦点
				node.container.on('blur', 'input', function(){
					node.container.removeClass('fselect-active');
					node.container.find('.fselect-drop').fadeOut('fast');
					app.setChoice(node);
				});
				
				//鼠标移入添加class
				node.container.on('hover', '.fselect-drop li', function(){
					$(this).addClass('active').siblings().removeClass('active');
				});
				
				//方向键
				node.container.on('keydown', 'input', function(event){
					if(event.keyCode == 38){//向上
						if(!node.drop.is(':hidden')){
							app.goUp(node);
						}
					}else if(event.keyCode == 40){//向下
						app.goDown(node);
					}
				});
				
				//输入框有输入
				node.container.on('keyup', 'input', function(event){
					var current = node.container.find('.fselect-results li.active');
					if(event.keyCode == 38){//向上
						
					}else if(event.keyCode == 40){//向下
						
					}else if(event.keyCode == 13 || event.keyCode == 108){//回车
						if(current.length > 0){
							app.setChoice(node, current.attr('data-value'));
						}
						node.container.find('.fselect-drop').hide();
						return;
					}else{
						app.showResults(node);
					}
				});

				//点击选择
				node.container.on('click', '.fselect-results li', function(){
					app.setChoice(node, $(this).attr('data-value'));
					node.input.blur();
				});
			},
			'init':function(node){
				this.setup(node);
				this.events(node);
			}
		};
		
		options = options || {};
		var settings = {
			'maxHeight':200,
			'afterSetChoice':function(o){}
		};
		if(typeof(options) == 'object'){//初始化
			$.each(options, function(i, n){
				settings[i] = n;
			});
			
			this.each(function(){
				var width = parseInt($(this).width());
				var padding_left = parseInt($(this).css('padding-left'));
				var padding_right = parseInt($(this).css('padding-right'));
				
				var node = {
					'settings':settings,
					'obj':$(this),//被隐藏掉到原始输入框
					'container':null,
					'drop':null,
					'input':null,//模拟出来到可见输入框
					'width':(width + padding_left + padding_right) + 'px',
					'height':(parseInt($(this).height()) + parseInt($(this).css('padding-top')) + parseInt($(this).css('padding-bottom'))) + 'px'
				};
				app.init(node);
				$(this).data('fselect', node);
			});
		}else if(typeof(options) == 'string'){
			if(options == 'update'){
				this.each(function(){
					app.update($(this).data('fselect'));
				});
			}
		}
	}
});