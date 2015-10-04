<section class="sec-1 mb30">
	<div class="container">
		<div class="row cols col3 cf">
			<?php F::widget()->load('about')?>
			<?php F::widget()->load('index-advantage')?>
			<?php F::widget()->load('environment')?>
		</div>
	</div>
</section>
<section class="sec-1 mb30 teachers">
	<div class="container cf">
		<?php F::widget()->load('abstract')?>
	</div>
</section>
<section class="sec-1 mb70">
	<div class="container">
		<div class="row cols col2 cf">
			<h2 class="sub-title">INFORMATION</h2>
			<?php F::widget()->load('news')?>
			<?php F::widget()->load('works')?>
		</div>
	</div>
</section>
<script>
$(function(){
	$('#environment').on('click', 'li a', function(){
		$(this).next('p').slideToggle();
		$(this).parent().toggleClass('act').siblings().removeClass('act').find('p').slideUp();
	});
});
</script>