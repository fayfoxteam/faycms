var common = {
	'toast':function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else if(type == 'error'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-middle'
				});
			}
		});
	},
	'form':function(){
		//表单提交
		$(document).on('click', 'a[id$="submit"]', function(){
			$('form#'+$(this).attr('id').replace('-submit', '')).submit();
			return false;
		});
	},
	'topPhone': function(){
		var phone_width = $('.phone-container .phone-number').show().width();
		$('.phone-container .phone-number').css({
			'width':0,
			'padding-left':0,
			'padding-right':0
		});
		$('.phone-container').on('mouseenter', function(){
			$('.phone-container .phone-number').animate({
				'width':phone_width,
				'padding-left':16,
				'padding-right':16
			});
		}).on('mouseleave', function(){
			$('.phone-container .phone-number').animate({
				'width':0,
				'padding-left':0,
				'padding-right':0
			});
		});
	},
	'events': function(){
		$('#right-fix-toolbar #skypes .close').on('click', function(){
			$('#right-fix-toolbar #skypes').fadeOut();
		});
		$('.toggle-phone-menu a').on('click', function(){
			$('.g-nav').slideToggle();
		});
	},
	'lightbox': function(){
		if($('[data-lightbox]').length){
			system.getCss(system.assets('css/lightbox/css/lightbox.css'), function(){
				system.getScript(system.assets('js/lightbox.min.js'), function(){
					lightbox.option({
						'albumLabel': '',
						'wrapAround': true
					});
				});
			});
		}
	},
	'init':function(){
		this.form();
		this.topPhone();
		this.events();
		this.lightbox();
	}
};