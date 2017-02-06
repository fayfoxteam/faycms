var arm = {
	/**
	 * 动画效果
	 */
	'animate': function(){
		var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');

		//履军职标题
		if($swiper.find('.job-title').length){
			$swiper.find('.job-title').show().addClass('rubberBand animated');
		}else{
			$('.job-title').hide().removeClass('rubberBand animated');
		}

		//兵种说明顶部文字
		if($swiper.find('.arm-text').length){
			$swiper.find('.arm-text').show().addClass('fadeInDown animated');
		}else{
			$('.arm-text').hide().removeClass('fadeInDown animated');
		}

		//防区说明顶部文字
		if($swiper.find('.defence-text').length){
			$swiper.find('.defence-text').show().addClass('fadeInDown animated');
		}else{
			$('.defence-text').hide().removeClass('fadeInDown animated');
		}

		//履军职任务列表
		if($swiper.find('.jobs').length){
			setTimeout(function(){
				$swiper.find('.jobs li:eq(0)').css({'visibility': 'visible'}).addClass('fadeIn animated');
			}, 300);
			setTimeout(function(){
				$swiper.find('.jobs li:eq(1)').css({'visibility': 'visible'}).addClass('fadeIn animated');
			}, 600);
			setTimeout(function(){
				$swiper.find('.jobs li:eq(2)').css({'visibility': 'visible'}).addClass('fadeIn animated');
			}, 900);
			setTimeout(function(){
				$swiper.find('.jobs li:eq(3)').css({'visibility': 'visible'}).addClass('fadeIn animated');
			}, 1200);
		}else{
			$('.jobs li').css({'visibility': 'hidden'}).removeClass('fadeIn animated');
		}
	},
	/**
	 * 循环动画，执行一次就好了，不用重复执行
	 */
	'interval': function(){
		//兵种文字
		if($('.arm-names').length){
			setInterval(function(){
				var $armNames = $('.arm-names');
				$armNames.find('.layer').removeClass('tada animated');
				//随机出一个0-5的整数
				var num = Math.floor(Math.random()*6);
				$armNames.find('.layer:eq('+num+')').addClass('tada animated');
			}, 1000);
		}

		//摇一摇标识
		if($('.shake').length){
			setInterval(function(){
				var $shake = $('.shake');
				if($shake.hasClass('animated')){
					$shake.removeClass('tada animated');
				}else{
					$shake.addClass('tada animated')
				}
			}, 1000);
		}
	},
	'setArm': function(){
		$.ajax({
			'type': 'GET',
			'url': system.url('api/arm/set'),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					var $arm6 = $('#arm-6');
					$arm6.find('.arms,.shake,.arm-names').remove();
					$arm6.append('<div class="layer result flip animated"><img src="'+resp.data.picture.url+'"></div>');
				}else{
					common.toast($resp.message, 'error');
				}
			}
		});
	},
	'setHour': function(){
		$.ajax({
			'type': 'GET',
			'url': system.url('api/hour/set'),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					var $arm8 = $('#arm-8');
					$arm8.find('.qiantong').remove();
					$arm8.append('<div class="layer result flip animated"><span class="hour">'+resp.data.name+'</span></div>');
				}else{
					common.toast($resp.message, 'error');
				}
			}
		});
	},
	'init': function(){
		this.animate();
		this.interval();
		common.swiper.on('SlideChangeStart', this.animate)
	}
};