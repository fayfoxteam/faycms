<?php
use fay\helpers\Html;
?>
<div class="box" id="box-publish-time" data-name="publish-time">
	<div class="box-title">
		<h4>链接地址</h4>
	</div>
	<div class="box-content">
		<?php echo F::form('widget')->inputText('link', array(
			'class'=>'form-control',
		))?>
		<div class="fc-grey">
			<p>若不为空，则图片外会套上&lt;a&gt;标签</p>
			<p>不要忘了http://</p>
		</div>
		<p><?php echo F::form()->select('target', array(
			'_blank'=>'_blank — 新窗口或新标签。',
			'_top'=>'_top — 顶层窗口或标签。',
			'_none'=>'_none — 同一窗口或标签。',
		), array(
			'class'=>'form-control',
		))?></p>
	</div>
</div>
<div class="box" id="box-tags">
	<div class="box-title">
		<h4>图片大小</h4>
	</div>
	<div class="box-content">
		<table class="form-table">
			<tr>
				<th>宽x高</th>
				<td>
					<?php echo Html::inputText('width', isset($config['width']) ? $config['width'] : '', array(
						'class'=>'form-control w50 ib',
					))?>
					x
					<?php echo Html::inputText('height', isset($config['height']) ? $config['height'] : '', array(
						'class'=>'form-control w50 ib',
					))?>
					<a href="javascript:;" title="原始尺寸" id="refresh-size"><i class="fa fa-refresh"></i></a>
				</td>
			</tr>
		</table>
	</div>
</div>
<script>
$(function(){
	$(document).delegate("#refresh-size", "click", function(){
		var image = new Image();
		image.src = $("#file-preview img").attr("src");
		$("input[name='width']").val(image.width);
		$("input[name='height']").val(image.height);
	});
});
</script>