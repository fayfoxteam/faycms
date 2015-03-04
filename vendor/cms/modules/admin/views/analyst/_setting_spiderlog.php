<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title">分页大小</label>
		<?php echo F::form('setting')->inputText('page_size', array(
			'class'=>'w35',
			'data-rule'=>'int',
			'data-params'=>'{max:100}',
			'data-label'=>'分页大小',
		))?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn-3',
		))?>
	</div>
<?php echo F::form('setting')->close()?>