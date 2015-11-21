/**
 * Toast插件，需要配合css
 */
;(function($){
	$.extend({
		'toast': function(message, type, params){
			//初始化参数
			type = type || 'success';
			params = params || {};
			var settings = {
				'closeButton': true,
				'positionClass': 'toast-top-right',
				'timeOut': 5000,//为0则不自动消失
				'onclick': null
			};
			for(var key in params){
				settings[key] = params[key];
			}
			
			//如果div不存在，则插入div
			if(!$('.toast-container.' + settings.positionClass).length){
				$('body').append('<div class="toast-container ' + settings.positionClass + '"></div>');
			}
			
			var $container = $('.toast-container.' + settings.positionClass);
			
			$container.append(['<div class="toast toast-', type, '">',
				(function(){
					if(settings.closeButton){
						return '<a class="toast-close-link">×</a>';
					}else{
						return '';
					}
				}()),
				'<div class="toast-message"><i></i>', message, '</div>',
			'</div>'].join(''));
			
			var $message = $container.find('.toast:last');
			
			if(settings.timeOut){
				var timeout = setTimeout((function($message){
					return function(){
						$message.fadeOut(function(){
							$(this).remove();
						});
					}
				})($message), settings.timeOut);
				
				$message.on('mouseover', function(){
					clearTimeout(timeout);
				}).on('mouseout', function(){
					timeout = setTimeout((function($message){
						return function(){
							$message.fadeOut(function(){
								$(this).remove();
							});
						}
					})($message), settings.timeOut);
				});
			}
			
			if(settings.closeButton){
				$message.find('.toast-close-link').on('click', function(){
					$(this).parent().fadeOut(function(){
						$(this).remove();
					});
				});
			}
			
			if(settings.click){
				$message.on('click', function(){
					settings.click($(this));
				});
			}
		}
	})
})(jQuery);