<?php
use fay\helpers\Html;
?>
<div class="box">
	<div class="box-title">
		<h4>列表</h4>
	</div>
	<div class="box-content">
		<div class="dragsort-list" id="widget-listing-values">
		<?php if(!empty($config['data'])){?>
			<?php foreach($config['data'] as $v){?>
				<div class="dragsort-item">
					<a class="dragsort-rm" href="javascript:;"></a>
					<a class="dragsort-item-selector"></a>
					<div class="dragsort-item-container">
						<?php echo Html::textarea("data[]", $v, array(
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
					<?php echo Html::textarea("data[]", '', array(
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
		<h4>渲染模板</h4>
	</div>
	<div class="box-content">
		<?php echo Html::textarea('template', isset($config['template']) ? $config['template'] : '', array(
			'class'=>'form-control h90 autosize',
		))?>
		<p class="fc-grey mt5">
			若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
			即类似<code>frontend/widget/template</code><br />
			则会调用当前application下符合该相对路径的view文件。<br />
			否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
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
				'<textarea name="data[]" class="form-control h60 autosize"></textarea>',
			'</div>',
			'<div class="clear"></div>',
		'</div>'].join(''));
		$('#widget-listing-data .dragsort-item:last-child textarea').autosize();
	});
});
</script>