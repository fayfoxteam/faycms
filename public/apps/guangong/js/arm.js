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
	'init': function(){
		this.animate();
		common.swiper.on('SlideChangeStart', this.animate)
	}
};