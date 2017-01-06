<?php use fay\services\FlashService;
$this->appendCss($this->appStatic('css/col2.css'))?>
<?php echo FlashService::get();?>
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
			<div class="tips">重置您的登陆密码</div>
			<form class="validform" method="post" id="form">
				<section class="clearfix box-4" style="margin-top:20px;">
					<div class="content fl">
						<div class="form-field-2">
							<label class="title-prompt-text" for="password">新密码</label>
							<?php echo \F::form()->inputPassword('password', array(
								'datatype'=>'*',
								'nullmsg'=>'新密码不能为空',
								'class'=>'inputxt long',
								'id'=>'password',
							))?>
						</div>
						<div class="form-field-2">
							<label class="title-prompt-text" for="repassword">确认密码</label>
							<?php echo \F::form()->inputPassword('repassword', array(
								'datatype'=>'*',
								'nullmsg'=>'确认密码不能为空',
								'recheck'=>'password',
								'class'=>'inputxt long',
								'id'=>'repassword',
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