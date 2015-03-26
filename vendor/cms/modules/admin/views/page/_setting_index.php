<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title">显示下列项目</label>
		<?php 
		echo F::form('setting')->inputCheckbox('cols[]', 'category', array(
			'label'=>'分类',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'status', array(
			'label'=>'状态',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'alias', array(
			'label'=>'别名',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'views', array(
			'label'=>'阅读数',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_modified_time', array(
			'label'=>'最后修改时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
			'label'=>'创建时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'sort', array(
			'label'=>'排序',
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