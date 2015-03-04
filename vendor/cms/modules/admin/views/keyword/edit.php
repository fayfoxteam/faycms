<?php
?>
<div class="col-2-3">
	<div class="col-right">
		<?php $this->renderPartial('_right');?>
	</div>
	<div class="col-left">
		<?php echo F::form()->open(array('admin/keyword/edit', F::app()->input->get()))?>
			<div class="form-field">
				<label>关键词<em class="color-red">*</em></label>
				<?php echo F::form()->inputText('keyword', array(
					'class'=>'full-width',
				))?>
			</div>
			<div class="form-field">
				<label>链接地址<em class="color-red">*</em></label>
				<?php echo F::form()->inputText('link', array(
					'class'=>'full-width',
				))?>
			</div>
			<div class="form-field">
				<?php echo F::form()->submitLink('编辑关键词', array(
					'class'=>'btn-1',
				))?>
			</div>
		<?php echo F::form()->close()?>
	</div>
</div>