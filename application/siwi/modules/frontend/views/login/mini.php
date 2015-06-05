<?php
?>
<div class="color-red"><?php if(isset($error)){echo $error;}?></div>
<form method="post" id="login-form" class="validform">
	<div class="form-field username">
		<label for="email" class="title-prompt-text">账&nbsp;号</label>
		<?php echo \F::form()->inputText('username', array(
			'class'=>'inputxt',
			'id'=>'email',
			'datatype'=>'*',
			'errormsg'=>'账号格式不正确',
			'nullmsg'=>'账号不能为空',
		))?>
	</div>
	<div class="form-field password">
		<label for="password" class="title-prompt-text">密&nbsp;码</label>
		<?php echo \F::form()->inputPassword('password', array(
			'class'=>'inputxt',
			'id'=>'password',
			'datatype'=>'*',
			'nullmsg'=>'密码不能为空',
		))?>
	</div>
	<div class="option">
		<a href="javascript:;" class="btn-1" id="login-form-submit">登&nbsp;&nbsp;录</a>
		<span class="links">
			<a href="<?php echo $this->url('login/forgot-password')?>" target="_top">忘记密码</a>
			|
			<a href="<?php echo $this->url('register-mini')?>">注册</a>
		</span>
		<div class="clear"></div>
	</div>
</form>
<script>
$(function(){
	$("#fancybox-content", window.parent.document).animate({'height':318}, 500);

	$("#login-form").delegate(":text,:password","keypress",function(event){
		if(event.keyCode==13){
			$("#login-form").submit();
		}
	});
});
</script>