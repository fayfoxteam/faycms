var common = {
	/**
	 * 表单实例
	 */
	'form': {
		'rules': {},
		'labels': {},
		'afterAjaxSubmit': function(){}
	},
	'swiper': null,
	'_swiper': function(){
		if($('.swiper-container').length){
			common.swiper = new Swiper('.swiper-container', {
				pagination: '.swiper-pagination',
				paginationClickable: true,
				//grabCursor: true,
				//loop: true,
				nextButton: '.swiper-btn-next',
				prevButton: '.swiper-btn-prev',
				'initialSlide': location.hash ? location.hash.substr(1) : 0
			});
			
			$('.swiper-to').on('click', function(){
				common.swiper.slideTo($(this).attr('data-slide'))
			});
		}
	},
	/**
	 * 表单提交
	 */
	'formSubmit': function(){
		$(document).on('click', 'a[id$="submit"]', function(){
			$('form#'+$(this).attr('id').replace('-submit', '')).submit();
			return false;
		});
	},
	/**
	 * 点击验证码后换一张
	 */
	'captcha': function(){
		$('.captcha').on('click', function(){
			common.changeCaptcha($(this));
		})
	},
	'changeCaptcha': function(o){
		$(o).attr('src', $(o).attr('src') + '?' + Math.random());
	},
	'validform': function(){
		if($('#form').length){
			system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
				common.form.obj = $('#form').validform(
					{
						'showAllErrors': false,
						'onError': function(obj, msg, rule){
							common.toast(msg, 'error');
						},
						'ajaxSubmit': true,
						'afterAjaxSubmit': common.form.afterAjaxSubmit
					},
					common.form.rules,
					common.form.labels
				);
			});
		}
	},
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
					'timeOut': 5000,
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
	/**
	 * 动画效果
	 */
	'animate': function(){
		var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		
		//步骤页面
		$steps = $swiper.find('.steps');
		if($steps.length){
			//若是滑倒步骤页
			$steps.find('a').hide();
			$steps.find('a:hidden:last').fadeIn('normal', function(){
				$steps.find('a:hidden:last').fadeIn('normal', function(){
					$steps.find('a:hidden:last').fadeIn('normal', function(){
						$steps.find('a:hidden:last').fadeIn('normal', function(){
							$steps.find('a:hidden:last').fadeIn('normal');
						})
					})
				});
			})
		}

		//一把刀，一个标题的页面
		if($swiper.find('.dadao').length){
			$swiper.find('.dadao').show().addClass('rotateInDownRight animated');
		}else{
			$('.dadao').hide().removeClass('rotateInDownRight animated');
		}
		if($swiper.find('.title').length){
			$swiper.find('.title').show().addClass('rotateInDownLeft animated');
		}else{
			$('.title').hide().removeClass('rotateInDownLeft animated');
		}
		
		//关公像
		
		
		//一些结果页
		if($swiper.find('.result').length){
			$swiper.find('.result').show().addClass('fadeIn animated');
		}else{
			$('.result').hide().removeClass('fadeIn animated');
		}
		
		//底部描述
		if($swiper.find('.description').length){
			$swiper.find('.description').show().addClass('lightSpeedIn animated');
		}else{
			$('.description').hide().removeClass('lightSpeedIn animated');
		}

		//右上角小标题
		if($swiper.find('.brand').length){
			$swiper.find('.brand').removeClass('fadeInRight animated');
			setTimeout(function(){
				$swiper.find('.brand').addClass('fadeInRight animated');
			}, 1);
		}
	},
	'init': function(){
		this._swiper();
		this.formSubmit();
		this.captcha();
		this.validform();
		this.animate();
		common.swiper.on('SlideChangeStart', this.animate);
	}
};
