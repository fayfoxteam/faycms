<?php
/**
 * @var $this \fay\core\View
 * @var $user array
 * @var $arm array
 */
?>
<div class="swiper-slide" id="recruit-41">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
	<div class="layer dadao"><img src="<?php echo $this->appAssets('images/recruit/dadao.png')?>"></div>
	<div class="layer title"><img src="<?php echo $this->appAssets('images/recruit/t4.png')?>"></div>
	<div class="layer description">
		<p>体验规则：</p>
		<p>完成报名手续并取得网络军籍之正式注册兵员访客接受将军密令，接受密令者需认真待之。</p>
	</div>
</div>
<div class="swiper-slide" id="recruit-42">
	<div class="layer guangongdianbing"><img src="<?php echo $this->appAssets('images/recruit/guangongdianbing.png')?>"></div>
	<div class="layer user-info">
		<fieldset>
			<label>识&nbsp;别&nbsp;号</label>
			<div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText('mobile', $user['user']['mobile'], array(
				'class'=>'form-control',
				'placeholder'=>'手机号为身份识别号',
			))?></div>
		</fieldset>
		<fieldset>
			<label>军团代号</label>
			<div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText(
				'daihao',
				$arm ? "关羽军团{$arm['name']}营{$user['user']['id']}" : '',
				array(
					'class'=>'form-control',
					'placeholder'=>'手机号为身份识别号',
				))?></div>
		</fieldset>
	</div>
	<div class="layer button">
		<?php if(!empty($user['user']['mobile'])){?>
			<a href="#jiangjunmiling-dialog" class="btn-1" id="jiangjunmiling-link">将军密令<br>报名可阅</a>
		<?php }else{?>
			<a href="#8" class="btn-1 swiper-to" data-slide="8">我要加入</a>
		<?php }?>
	</div>
</div>
<div class="hide">
	<div id="jiangjunmiling-dialog" class="dialog">
		<div class="dialog-content">
			<img src="<?php echo $this->appAssets('images/recruit/yinzhang-text.png')?>" class="yinzhang-text">
			<img src="<?php echo $this->appAssets('images/recruit/yinzhang.png')?>" class="yinzhang">
		</div>
	</div>
</div>
<?php $this->renderPartial('_js')?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<script>
	$('#jiangjunmiling-link').fancybox({
		'type': 'inline',
		'centerOnScroll': true,
		'padding': 0,
		'showCloseButton': false,
		'width': '80%'
	});
</script>