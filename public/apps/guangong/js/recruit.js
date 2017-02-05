var recruit = {
	/**
	 * 动画效果
	 */
	'animate': function(){
		$swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		//首页第二屏
		if($swiper.attr('id') == 'recruit-2'){
			$('#recruit-2 .text').show().addClass('slideInDown animated');
		}else{
			$('#recruit-2 .text').hide().removeClass('slideInDown animated');
		}
		
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
	},
	'init': function(){
		this.animate();
		common.swiper.on('SlideChangeStart', recruit.animate)
	}
};