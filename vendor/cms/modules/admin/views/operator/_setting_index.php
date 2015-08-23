<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">显示下列项目</label>
		<?php 
		echo F::form('setting')->inputCheckbox('cols[]', 'avatar', array(
			'label'=>'头像',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'roles', array(
			'label'=>'角色',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'mobile', array(
			'label'=>'手机',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'email', array(
			'label'=>'邮箱',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'nickname', array(
			'label'=>'昵称',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'realname', array(
			'label'=>'真名',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'status', array(
			'label'=>'状态',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'block', array(
			'label'=>'阻塞',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'reg_time', array(
			'label'=>'注册时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'reg_ip', array(
			'label'=>'注册IP',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_login_time', array(
			'label'=>'最后登陆时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_login_ip', array(
			'label'=>'最后登陆IP',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_time_online', array(
			'label'=>'最后在线时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'trackid', array(
			'label'=>'Trackid',
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