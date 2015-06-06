<?php
use fay\helpers\Html;
?>
<div id="login-panel">
	<?php if(empty($error)){?>
		<h1>登录平台账号</h1>
	<?php }else{?>
		<div id="error-msg" class="notification error"><?php echo Html::encode($error)?></div>
	<?php }?>
	<form method="post" id="login-form" class="validform">
		<div id="login-form-container">
			<fieldset class="form-field">
				<label for="login-username" class="prompt-text">账号</label>
				<img src="<?php echo $this->url()?>static/51fb/images/man.png" />
				<?php echo \F::form()->inputText('username', array(
					'id'=>'login-username',
					'datatype'=>'e',
					'nullmsg'=>'账号不能为空',
					'errormsg'=>'账号应为邮箱格式',
				))?>
			</fieldset>
			<fieldset class="last form-field">
				<label for="login-password" class="prompt-text">密码</label>
				<img src="<?php echo $this->url()?>static/51fb/images/lock.png" />
				<?php echo \F::form()->inputPassword('password', array(
					'id'=>'login-password',
					'datatype'=>'*',
					//'nullmsg'=>'密码不能为空',
					'ignore'=>'ignore',
				))?>
			</fieldset>
		</div>
		<div id="login-options" class="clearfix">
			<a href="<?php echo $this->url('login/forgot-password')?>" class="fr">忘记密码？</a>
		</div>
		<a href="javascript:;" id="login-form-submit">登&nbsp;&nbsp;录</a>
		<p id="login-register-container">
			还没注册？
			<a href="<?php echo $this->url('register', array(
				'return_url'=>$this->input->get('return_url', '', false),
			), false)?>">立即注册</a>
		</p>
	</form>
</div>