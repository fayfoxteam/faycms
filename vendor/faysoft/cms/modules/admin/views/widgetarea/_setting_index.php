<?php echo F::form('setting')->open(array('cms/admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">显示别名</label>
		<?php
		echo F::form('setting')->inputRadio('show_alias', '1', array(
			'label'=>'显示',
		), true);
		echo F::form('setting')->inputRadio('show_alias', '0', array(
			'label'=>'不显示',
		), true);
		?>
		<p class="fc-grey">别名用于代码调用，普通用户一般不需要关心这个</p>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn btn-sm',
		))?>
	</div>
<?php echo F::form('setting')->close()?>