<?php

use fay\models\Flash;
$this->appendCss($this->staticFile('css/col2.css'));
echo Flash::get();
?>
<div class="col2 pb30">
	<div class="w1000 clearfix">
		<div class="left">
			<aside class="box-3">
				<h3>
					<span class="en">Options</span>
					<span class="ch">其它操作</span>
				</h3>
				<div class="content">
					<ul class="menu">
						<li><a href="<?php echo $this->url('login')?>" class="register-link">登陆</a></li>
						<li><a href="<?php echo $this->url('register')?>" class="register-link">注册</a></li>
						<li><a href="<?php echo $this->url('login/forgot-password')?>" class="current">找回密码</a></li>
					</ul>
				</div>
			</aside>
		</div>
		<div class="main">
			<div class="tips">Hi，请使用注册邮箱找回密码</div>
			<form class="validform" method="post" id="form">
				<section class="clearfix box-4" style="margin-top:20px;">
					<div class="content fl">
						<div class="form-field-2">
							<label class="title-prompt-text" for="email">注册邮箱</label>
							<?php echo \F::form()->inputText('email', array(
								'datatype'=>'e',
								'errormsg'=>'邮箱格式不正确',
								'nullmsg'=>'邮箱不能为空',
								'class'=>'inputxt long',
								'ajaxurl'=>$this->url('system/is-username-exist'),
								'id'=>'email'
							))?>
						</div>
					</div>
				</section>
				<a href="javascript:;" class="btn-2" id="form-submit" style="float:left;">确认提交</a>
			</form>
			<div class="clear"></div>
		</div>
	</div>
</div>