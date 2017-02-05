var arm = {
	/**
	 * 动画效果
	 */
	'animate': function(){
		var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		
	},
	'init': function(){
		this.animate();
		common.swiper.on('SlideChangeStart', this.animate)
	}
};