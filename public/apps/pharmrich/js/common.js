var common = {
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
	'init':function(){
		this.form();
		this.topPhone();
	}
};