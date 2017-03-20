<div class="swiper-slide" id="arm-12">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
	<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t5.png')?>"></div>
	<div class="layer description">
		<p class="center">有价值有深度的关公文化网络体验之旅</p>
		<p class="center">为实战体验做战争准备</p>
	</div>
</div>
<div class="swiper-slide jobs-slide" id="arm-13">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer subtitle">履军职</div>
	<div class="layer job-title"><img src="<?php echo $this->appAssets('images/arm/junzhi-title.png')?>"></div>
	<div class="layer jobs">
		<ul>
			<li class="job-1">
				<a href="#attendance-dialog" class="fancybox-inline task-link" data-task-id="1"><img src="<?php echo $this->appAssets('images/arm/junzhi-1.png')?>"></a>
			</li>
			<li class="job-2">
				<a href="javascript:" class="show-weixin-share-link"><img src="<?php echo $this->appAssets('images/arm/junzhi-2.png')?>"></a>
			</li>
			<li class="job-3">
				<a href="<?php
					if($next_post){
						echo \fay\helpers\UrlHelper::createUrl('post/item', array(
							'id'=>$next_post,
						));
					}else{
						echo 'javascript:';
					}
				?>"><img src="<?php echo $this->appAssets('images/arm/junzhi-3.png')?>"></a>
			</li>
			<li class="job-4">
				<a href="<?php echo $this->url()?>" class=""><img src="<?php echo $this->appAssets('images/arm/junzhi-4.png')?>"></a>
			</li>
		</ul>
	</div>
	<div class="layer description"><img src="<?php echo $this->appAssets('images/arm/junzhi-text.png')?>"></div>
</div>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config(<?php echo $js_sdk_config?>);
wx.ready(function(){
	wx.onMenuShareTimeline({
		title: '天下招募令', // 分享标题
		link: '<?php echo $this->url('recruit')?>', // 分享链接
		imgUrl: '<?php echo $this->appAssets('images/arm/guanyin.png')?>', // 分享图标
		success: function(){
			// 用户确认分享后执行的回调函数
			$('body').block();
			$.ajax({
				'type': 'POST',
				'url': system.url('api/task/do'),
				'data': {'task_id': 2},
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					$('body').unblock();
					if(resp.status){
						common.toast('分享朋友圈任务完成', 'success');
					}
				}
			});
		},
		cancel: function () {
			// 用户取消分享后执行的回调函数
		}
	});
});
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
				$('body').unblock();
			}
		});
	});
});
</script>
