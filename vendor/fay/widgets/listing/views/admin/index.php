<?php
use fay\helpers\Html;
?>
<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>列表</h4>
	</div>
	<div class="box-content">
		<div class="dragsort-list" id="widget-listing-values">
		<?php if(!empty($data['values'])){?>
			<?php foreach($data['values'] as $v){?>
				<div class="dragsort-item">
					<a class="dragsort-rm" href="javascript:;"></a>
					<a class="dragsort-item-selector"></a>
					<div class="dragsort-item-container">
						<?php echo Html::textarea("values[]", $v, array(
							'class'=>'form-control h60 autosize',
						));?>
					</div>
					<div class="clear"></div>
				</div>
			<?php }?>
		<?php }else{?>
			<div class="dragsort-item">
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<?php echo Html::textarea("values[]", '', array(
						'class'=>'form-control h60 autosize',
					));?>
				</div>
				<div class="clear"></div>
			</div>
		<?php }?>
		</div>
		<?php echo Html::link('添加', 'javascript:;', array(
			'class'=>'btn mt5',
			'id'=>'widget-add-value-link',
		))?>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>渲染模板</h4>
	</div>
	<div class="box-content">
		<?php echo Html::textarea('template', isset($data['template']) ? $data['template'] : '', array(
			'class'=>'form-control h90 autosize',
		))?>
		<p class="fc-grey mt5">
			循环列表调用该模版渲染。
			<span class="fc-orange">{$value}</span>代表“值”。
			例如：<code><?php echo Html::encode('<p>{$value}</p>')?></code>
		</p>
	</div>
</div>
<script>
$(function(){
	$(document).on('click', '#widget-add-value-link', function(){
		$('#widget-listing-values').append(['<div class="dragsort-item">',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<textarea name="values[]" class="form-control h60 autosize"></textarea>',
			'</div>',
			'<div class="clear"></div>',
		'</div>'].join(''));
		$('#widget-listing-values .dragsort-item:last-child textarea').autosize();
	});
});
</script>