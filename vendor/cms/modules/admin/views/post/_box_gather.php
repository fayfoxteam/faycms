<?php
use fay\helpers\Html;
?>
<div class="box" id="box-gather" data-name="gather">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>采集器</h4>
	</div>
	<div class="box-content">
		<a href="#gather-dialog" class="btn fancybox-inline">采集页面</a>
		<div class="color-grey">利用jquery选择器，进行单页面采集</div>
	</div>
</div>
<div class="hide">
	<div id="gather-dialog" class="common-dialog">
		<div class="common-dialog-content">
			<h4>文章采集</h4>
			<div id="gather-form">
				<div class="form-field">
					<label class="title">链接地址</label>
					<?php echo Html::inputText('', '', array(
						'class'=>'w400',
						'id'=>'gather-url',
					))?>
					<p class="description">例子：http://www.fayfox.com/about.html —— 不要忘了 http://</p>
				</div>
				<div class="form-field">
					<label class="title">选择器</label>
					<?php echo Html::inputText('gather_rule', '', array(
						'class'=>'w400',
						'id'=>'gather-rule',
					))?>
					<p class="description">例子：$("<span class="color-red">.post-content</span>").html()，如红色部分的选择器。</p>
				</div>
				<div class="form-field">
					<a href="javascript:;" class="btn" id="gather-form-submit-ajax">采集</a>
					<a href="javascript:;" class="btn-2 fancybox-close">取消</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	$(document).delegate("#gather-form-submit-ajax", "click", function(){
		$(this).parent().append('<img src="'+system.url('images/throbber.gif')+'" class="submit-loading" />');
		$.ajax({
			type: "GET",
			url: system.url("admin/gather/get-url"),
			data: {
				'url':$("#gather-url").val()
			},
			success: function(resp){
				if($.browser.msie && $.browser.version < 9){
					common.editorObj.html($($("#gather-rule").val(), resp).html());
				}else{
					common.editorObj.setData($($("#gather-rule").val(), resp).html());
				}
				$("#gather-form-submit-ajax").parent().find("img").remove();
				$.fancybox.close();
			}
		});
	});
});
</script>