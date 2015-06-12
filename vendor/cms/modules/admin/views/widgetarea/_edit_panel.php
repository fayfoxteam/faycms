<?php
use fay\helpers\Html;
?>
<div class="form-field">
	<label class="title">别名<em class="required">*</em></label>
	<?php echo F::form()->inputText('alias', array(
		'class'=>'form-control',
	))?>
	<p class="fc-grey">前台通过别名来调用小工具域</p>
</div>
<div class="form-field">
	<label class="title">描述</label>
	<?php echo F::form()->textarea('description', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>
<div class="form-field">
	<label class="title">小工具实例</label>
	<div class="dragsort-list" id="prop-list">
	<?php if(isset($widgets) && is_array($widgets)){?>
		<?php foreach($widgets as $w){?>
			<div class="dragsort-item">
				<?php echo Html::inputHidden('widgets[]', $w['id'])?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<?php echo $w['description'], ' - ', $w['alias']?>
				</div>
			</div>
		<?php }?>
	<?php }?>
	</div>
</div>