<?php
/**
 * @var $this \fay\core\View
 * @var $group array
 */
$this->appendCss($this->appStatic('css/group.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-31">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第三式</span>
				<span class="title">盟誓</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
		</div>
	</div>
</div>
<script>
	common.form.afterAjaxSubmit = function(resp){
		if(resp.status){
			alert('准备跳转')
		}else{
			common.toast(resp.message, 'error');
		}
	}
</script>
<script type="text/javascript" src="<?php echo $this->appStatic('js/group.js')?>"></script>
<script>group.init();</script>