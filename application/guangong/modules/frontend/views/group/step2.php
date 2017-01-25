<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appStatic('css/group.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-21">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第二式</span>
				<span class="title">拜帖</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
			<div class="layer explain">
				<p>使用说明：</p>
				<p>发起者输入姓名在本系统关公点兵网络军籍档案获取个人信息，其他结义者依次进行，按提交结义人数完成拜帖信息获取，由发起者履行排序程序。</p>
			</div>
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