<?php
/**
 * @var $sign_up_days int
 * @var $attendances int
 */
?>
<div class="hide">
	<div id="attendance-dialog" class="dialog">
		<div class="dialog-content">
			<div class="nickname"><?php echo $user['user']['nickname']?></div>
			<div class="sign-up-days">今天是服役第<span class="content"><?php echo $sign_up_days?></span>天</div>
			<div class="attendance-count">您已经按规履行军职<span class="content"><?php echo $attendances?></span>天</div>
			<div class="yin"><img src="<?php echo $this->appAssets('images/arm/guanyin.png')?>"></div>
		</div>
	</div>
</div>
<script>
$(function(){
	common.swiper.on('SlideChangeEnd', function(){
		$activeSlide = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
		if($activeSlide.hasClass('jobs-slide')){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function() {
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function () {
					$.fancybox($('#attendance-dialog').parent().html());
				});
			});
		}
	});
});
</script>