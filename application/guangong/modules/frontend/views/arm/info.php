<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="arm-9">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t4.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-10">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">录军籍</div>
			<div class="layer juanzhou-he"><img src="<?php echo $this->appAssets('images/arm/juanzhou-he.png')?>"></div>
			<div class="layer juanzhou-kai">
				<fieldset id="info-avatar">
					<img src="">
				</fieldset>
				<fieldset>
					<label class="label-title">识别号</label>
					<span class="content" id="info-mobile"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">出生期</label>
					<span class="content" id="info-birthday"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">所在地</label>
					<span class="content" id="info-region"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">报名期</label>
					<span class="content" id="info-sign-up-time"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">服役期</label>
					<span class="content" id="info-army-time"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">防区</label>
					<span class="content" id="info-defence-area"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">兵种</label>
					<span class="content" id="info-arm"></span>
				</fieldset>
				<fieldset>
					<label class="label-title">军职</label>
					<span class="content" id="info-rank"></span>
				</fieldset>
			</div>
			<div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
			<div class="layer description">
				<p>规则说明：</p>
				<p>个人身份信息、防区、兵种确定后，即时输入网络军籍档案系统、信息不可更改。微官网可随时查询网络军籍档案。</p>
			</div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
$(function(){
	//初始化用户信息
	$.ajax({
		'type': 'GET',
		'url': system.url('api/user/info'),
		'data': {'user_id': system.user_id},
		'dataType': 'json',
		'cache': false,
		'success': function(resp){
			if(resp.status){
				$('#info-avatar img').attr('src', resp.data.user.avatar.thumbnail);
				$('#info-mobile').text(resp.data.user.mobile);
				$('#info-birthday').text(resp.data.extra.birthday);
				$('#info-region').text(resp.data.extra.state_name + ' ' + resp.data.extra.city_name + ' ' + resp.data.extra.district_name);
				if(resp.data.extra.sign_up_time != 0){
					$('#info-sign-up-time').text(system.date(resp.data.extra.sign_up_time, true));
					$('#info-army-time').text(system.date(parseInt(resp.data.extra.sign_up_time) + 86400 * 365, true));
				}
				$('#info-defence-area').text(resp.data.extra.defence_area_name);
				$('#info-arm').text(resp.data.extra.arm_name);
				$('#info-rank').text(resp.data.extra.rank_name);
			}else{
				common.toast(resp.message, 'error');
			}
		}
	});
	
	$.shake(function(){
		//摇一摇显示军籍
		if(arm.enableShake && common.swiper.activeIndex == 2){
			arm.showInfo();
		}
	});
	$('.shake').on('click', function(){
		arm.showInfo();
	});
});
</script>