var recruit = {
	/**
	 * 动画效果
	 */
	'animate': function(){
		var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		//首页第二屏
		if($swiper.attr('id') == 'recruit-2'){
			$('#recruit-2 .text').show().addClass('slideInDown animated');
		}else{
			$('#recruit-2 .text').hide().removeClass('slideInDown animated');
		}

		if($swiper.find('.zhaomuling').length){
			$swiper.find('.zhaomuling').fadeIn().addClass('slideInDown animated');
		}else{
			$('.zhaomuling').hide().removeClass('slideInDown animated');
		}

		if($swiper.find('.yi-text').length){
			$swiper.find('.yi-text').fadeIn().addClass('slideInLeft animated');
		}else{
			$('.yi-text').hide().removeClass('slideInLeft animated');
		}
		if($swiper.find('.yi').length){
			$swiper.find('.yi').fadeIn().addClass('flip animated');
		}else{
			$('.yi').hide().removeClass('flip animated');
		}
	},
	'init': function(){
		this.animate();
		common.swiper.on('SlideChangeStart', this.animate);
	}
};