<?php
?>
<div class="row">
	<div class="col-5">
		<?php echo F::form()->open(array('admin/keyword/edit', F::app()->input->get()))?>
			<div class="form-field">
				<label>关键词<em class="required">*</em></label>
				<?php echo F::form()->inputText('keyword', array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label>链接地址<em class="required">*</em></label>
				<?php echo F::form()->inputText('link', array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<?php echo F::form()->submitLink('编辑关键词', array(
					'class'=>'btn',
				))?>
			</div>
		<?php echo F::form()->close()?>
	</div>
	<div class="col-7">
		<?php $this->renderPartial('_right');?>
	</div>
</div>