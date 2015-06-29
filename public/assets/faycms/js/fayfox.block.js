/**
 * 将所选元素覆盖起来
 */
jQuery.fn.extend({
	'block': function(options){
		options = options || {};
		this.each(function(){
			if($(this).is(':visible')){//对于不可见的元素，就不加覆盖层了
				$(this).unblock();
				
				var offset = $(this).offset();
				var width = $(this).width();
				var height = $(this).height();
				var padding_top = $(this).css('padding-top');
				var padding_bottom = $(this).css('padding-bottom');
				var padding_left = $(this).css('padding-left');
				var padding_right = $(this).css('padding-right');
				var border_left = $(this).css('border-left-width');
				var border_top = $(this).css('border-top-width');
				
				var html = [
					'<div>',
						'<div class="fn-block"></div>',
						'<img src="', system.url('images/ajax-loading.gif'), '" class="fn-block-loading-img" />',
					'</div>'
				].join('');
				
				var html = $('<div class="fblock">').css({
					'width':parseInt(width) + parseInt(padding_left) + parseInt(padding_right),
					'height':parseInt(height) + parseInt(padding_top) + parseInt(padding_bottom),
					'position':$(this).context.tagName == 'BODY' ? 'fixed' : 'absolute',
					'left':parseInt(offset.left) + parseInt(border_left),
					'top':parseInt(offset.top) + parseInt(border_top),
					'z-index':typeof(options.zindex) == 'undefined' ? 500 : options.zindex
				})
					.append('<div class="fn-block"></div>')
					.append('<img src="' + system.url('images/ajax-loading.gif') + '" class="fn-block-loading-img" />');
				$('body').append(html);
				$(this).data('fblock', html);
			}
		});
	},
	'unblock':function(options){
		if(options == 'clear'){
			//删除所有覆盖层
			$(".fblock").remove();
		}else{
			this.each(function(){
				if(typeof($(this).data('fblock')) != 'undefined'){
					if(options == 'immediately'){
						$(this).data('fblock').remove();
						$(this).removeData('fblock');
					}else{
						$(this).data('fblock').fadeOut('normal', function(){
							$(this).remove();
						}).removeData('fblock');
					}
				}
			});
		}
	}
})