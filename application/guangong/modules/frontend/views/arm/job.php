<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="arm-12">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t5.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-13">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">履军职</div>
			<div class="layer job-title"><img src="<?php echo $this->appAssets('images/arm/junzhi-title.png')?>"></div>
			<div class="layer jobs">
				<ul>
					<li class="job-1">
						<a href="<?php echo $this->url()?>" class="task-link" data-task-id="1"><img src="<?php echo $this->appAssets('images/arm/junzhi-1.png')?>"></a>
					</li>
					<li class="job-2">
						<a href="<?php echo $this->url()?>" class="task-link" data-task-id="2"><img src="<?php echo $this->appAssets('images/arm/junzhi-2.png')?>"></a>
					</li>
					<li class="job-3">
						<a href="<?php echo $this->url()?>" class="task-link" data-task-id="3"><img src="<?php echo $this->appAssets('images/arm/junzhi-3.png')?>"></a>
					</li>
					<li class="job-4">
						<a href="<?php echo $this->url()?>" class="task-link" data-task-id="4"><img src="<?php echo $this->appAssets('images/arm/junzhi-4.png')?>"></a>
					</li>
				</ul>
			</div>
			<div class="layer description"><img src="<?php echo $this->appAssets('images/arm/junzhi-text.png')?>"></div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>
<script>
$(function(){
	$('.task-link').on('click', function(){
		$('body').block();
		var href = $(this).attr('href');
		//记录任务
		$.ajax({
			'type': 'POST',
			'url': system.url('api/task/do'),
			'data': {'task_id': $(this).attr('data-task-id')},
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					//跳转
					window.location.href = href;
				}
			}
		});
	});
});
</script>