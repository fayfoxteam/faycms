<?php
?>
<form method="post" id="register-form" class="validform">
	<div class="form-field email">
		<label for="email" class="title-prompt-text">您的用户名</label>
		<?php echo \F::form()->inputText('username', array(
			'class'=>'inputxt',
			'id'=>'username',
			'datatype'=>'s2-20',
			'errormsg'=>'用户名长度需在2-20个字符之间',
			'nullmsg'=>'用户名不能为空',
			'ajaxurl'=>$this->url('system/is-username-not-exist'),
		))?>
	</div>
	<div class="form-field password">
		<label for="password" class="title-prompt-text">您的密码</label>
		<?php echo \F::form()->inputPassword('password', array(
			'class'=>'inputxt',
			'id'=>'password',
			'datatype'=>'*',
			'nullmsg'=>'密码不能为空',
		))?>
	</div>
	<div class="form-field password">
		<label for="repassword" class="title-prompt-text">确认密码</label>
		<?php echo \F::form()->inputPassword('repassword', array(
			'class'=>'inputxt',
			'id'=>'repassword',
			'recheck'=>'password',
			'datatype'=>'*',
			'nullmsg'=>'确认密码不能为空',
		))?>
	</div>
	<div class="option">
		<a href="javascript:;" class="btn-1" id="register-form-submit">完&nbsp;&nbsp;成</a>
		<span class="links">
			已有账号
			|
			<a href="<?php echo $this->url('login-mini')?>">登录</a>
		</span>
		<div class="clear"></div>
	</div>
</form>
<script>
$(function(){
	$("#fancybox-content", window.parent.document).animate({'height':368}, 500, null, function(){
		parent.$.fancybox.center();
	});
});
</script>