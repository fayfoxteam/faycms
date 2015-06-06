<?php
use fay\helpers\Html;
?>
<div class="box" id="box-files" data-name="files">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>路线攻略</h4>
	</div>
	<div class="box-content">
		<p class="color-grey">说明：回车符会被自动转为p标签包裹</p>
		<div id="upload-file-container">
			<?php echo Html::link('新增路线', 'javascript:;', array(
				'class'=>'btn',
				'id'=>'add-route-link',
			))?>
		</div>
		<div class="dragsort-list route-list">
		<?php if(!empty($routes)){?>
			<?php foreach($routes as $r){?>
				<div class="dragsort-item">
					<a class="dragsort-rm" href="javascript:;"></a>
					<a class="dragsort-item-selector"></a>
					<div class="dragsort-item-container">
						<?php echo Html::textarea("route[{$r['id']}]", $r['route'], array(
							'class'=>'form-control h90 autosize',
							'placeholder'=>'路线攻略',
						));?>
					</div>
					<div class="clear"></div>
				</div>
			<?php }?>
		<?php }?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<script>
$(function(){
	$(document).on('click', '#add-route-link', function(){
		$('.route-list').append(['<div class="dragsort-item">',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<textarea name="route[new', Math.random(), ']" class="form-control h90 autosize" placeholder="路线攻略"></textarea>',
			'</div>',
			'<div class="clear"></div>',
		'</div>'].join(''));
	});
});
</script>