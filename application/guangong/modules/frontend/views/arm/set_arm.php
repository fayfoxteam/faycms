<?php
/**
 * @var $this \fay\core\View
 * @var $arm array
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="arm-5">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t2.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-6">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">选兵种</div>
			<div class="layer mountain"><img src="<?php echo $this->appAssets('images/arm/mountain.png')?>"></div>
			<?php if($arm){?>
				<a class="layer result" href="#arm-dialog"><img src="<?php echo $arm['picture']['url']?>"></a>
			<?php }else{?>
				<div class="layer arms"><img src="<?php echo $this->appAssets('images/arm/arms.png')?>"></div>
				<div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
			<?php }?>
			<div class="layer arm-text"><img src="<?php echo $this->appAssets('images/arm/arm-text.png')?>"></div>
			<div class="layer description">
				<p>规则说明：</p>
				<p>关羽军团所募兵员兵种分配采取随机分配原则，由手机摇一摇自行确定，一经确定不可更改，兵种分别为步兵、骑兵、水军、弩兵、车兵。</p>
			</div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<div class="hide">
	<div id="arm-dialog" class="dialog">
		<div class="dialog-content">
			<h1><?php echo $arm['name']?>营</h1>
			<div class="arm-description"><?php echo \fay\helpers\StringHelper::nl2p($arm['description'])?></div>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<?php if(!$arm){?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
	$.shake(function(){
		//摇一摇触发排勤务
		if(common.swiper.activeIndex == 2){
			$.ajax({
				'type': 'GET',
				'url': system.url('api/arm/set'),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					if(resp.status){
						var $arm6 = $('#arm-6');
						$arm6.find('.arms').remove();
						$arm6.find('.shake').remove();
						$arm6.append('<div class="layer result"><img src="'+resp.data.picture.url+'"></div>');
					}else{
						common.toast($resp.message, 'error');
					}
				}
			});
		}
	});
</script>
<?php }?>
<script>
	$('#arm-6 .result').fancybox({
		'type': 'inline',
		'centerOnScroll': true,
		'padding': 0,
		'showCloseButton': false,
		'width': '80%'
	});
</script>