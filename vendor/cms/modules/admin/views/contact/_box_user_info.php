<div class="box" id="box-user-info" data-name="user_info">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>用户信息</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title">姓名</label>
			<?php echo F::form()->inputText('name', array(
				'class'=>'form-control',
			))?>
		</div>
		<div class="form-field">
			<label class="title">邮箱</label>
			<?php echo F::form()->inputText('email', array(
				'class'=>'form-control',
			))?>
		</div>
		<div class="form-field">
			<label class="title">电话</label>
			<?php echo F::form()->inputText('mobile', array(
				'class'=>'form-control',
			))?>
		</div>
		<div class="form-field">
			<label class="title">国家</label>
			<?php echo F::form()->inputText('country', array(
				'class'=>'form-control',
			))?>
		</div>
	</div>
</div>