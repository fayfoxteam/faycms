<?php echo F::form('setting')->open(array('cms/admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">分页大小</label>
		<?php echo F::form('setting')->inputNumber('page_size', array(
			'class'=>'form-control w50',
			'min'=>1,
			'max'=>999,
		))?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn btn-sm',
		))?>
	</div>
<?php echo F::form('setting')->close()?>