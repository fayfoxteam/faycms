<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title">显示用户方式</label>
		<?php
		echo F::form('setting')->inputRadio('display_name', 'username', array(
			'label'=>'用户名',
		), true);
		echo F::form('setting')->inputRadio('display_name', 'nickname', array(
			'label'=>'昵称',
		));
		echo F::form('setting')->inputRadio('display_name', 'realname', array(
			'label'=>'真名',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title">分页大小</label>
		<?php echo F::form('setting')->inputText('page_size', array(
			'class'=>'form-control w50',
		))?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn btn-sm',
		))?>
	</div>
<?php echo F::form('setting')->close()?>