<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">显示下列项目</label>
		<?php 
		echo F::form('setting')->inputCheckbox('cols[]', 'area', array(
			'label'=>'地域',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'ip', array(
			'label'=>'IP',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'url', array(
			'label'=>'入口页面',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
			'label'=>'访问时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'site', array(
			'label'=>'站点',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'trackid', array(
			'label'=>'Trackid',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'refer', array(
			'label'=>'来源',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'views', array(
			'label'=>'打开次数',
		));
		?>
	</div>
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