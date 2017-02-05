var group = {
	/**
	 * 动画效果
	 */
	'animate': function(){
		var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		//首页第二屏
		if($swiper.find('.layer-1').length){
			$swiper.find('.layer-1').show().addClass('fadeIn animated');
		}else{
			$('.layer-1').hide().removeClass('fadeIn animated');
		}
	},
	'init': function(){
		this.animate();
		common.swiper.on('SlideChangeStart', this.animate);
	}
};