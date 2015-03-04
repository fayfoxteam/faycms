<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title">显示下列项目</label>
		<?php 
		echo F::form('setting')->inputCheckbox('cols[]', 'realname', array(
			'label'=>'姓名',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'email', array(
			'label'=>'邮箱',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'phone', array(
			'label'=>'电话',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
			'label'=>'留言时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'area', array(
			'label'=>'地域',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'ip', array(
			'label'=>'IP',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title">显示时间</label>
		<?php
		echo F::form('setting')->inputRadio('display_time', 'short', array(
			'label'=>'简化时间',
		), true);
		echo F::form('setting')->inputRadio('display_time', 'full', array(
			'label'=>'完整时间',
		));
		?>
	</div>
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