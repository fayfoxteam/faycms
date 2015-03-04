<?php
?>
<div class="col-2-3">
	<div class="col-right">
		<?php $this->renderPartial('_right');?>
	</div>
	<div class="col-left">
		<?php echo F::form()->open(array('admin/option/create'))?>
			<div class="form-field">
				<label class="title">键<em class="color-red">*</em></label>
				<?php echo F::form()->inputText('option_name', array('class'=>'full-width'))?>
			</div>
			<div class="form-field">
				<label class="title">值</label>
				<?php echo F::form()->textarea('option_value', array('class'=>'full-width', 'rows'=>5))?>
			</div>
			<div class="form-field">
				<label class="title">描述</label>
				<?php echo F::form()->textarea('description', array('class'=>'full-width', 'rows'=>5))?>
			</div>
			<div class="form-field">
				<?php echo F::form()->submitLink('添加参数', array(
					'class'=>'btn-1',
				))?>
			</div>
		<?php echo F::form()->close()?>
	</div>
</div>