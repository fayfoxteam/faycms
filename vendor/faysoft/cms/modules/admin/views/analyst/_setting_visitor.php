<?php echo F::form('setting')->open(array('cms/admin/system/setting'))?>
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
		echo F::form('setting')->inputCheckbox('cols[]', 'se', array(
			'label'=>'搜索引擎',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'keywords', array(
			'label'=>'关键词',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'browser', array(
			'label'=>'浏览器内核',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'browser_version', array(
			'label'=>'内核版本',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'shell', array(
			'label'=>'浏览器套壳',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'shell_version', array(
			'label'=>'套壳版本',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'os', array(
			'label'=>'操作系统',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'ua', array(
			'label'=>'UA',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'screen', array(
			'label'=>'屏幕大小',
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