<?php
use fay\helpers\Html;
?>
<div id="login-panel" class="logged">
	<?php if(empty($error)){?>
		<h1>登录平台账号</h1>
	<?php }else{?>
		<div id="error-msg" class="notification error"><?php echo Html::encode($error)?></div>
	<?php }?>
	<div id="logged-container">
		<p>您已登陆账号</p>
		<p class="account">
			<?php echo \F::session()->get('user.username')?>
			( <?php echo Html::link('退出', array('user/logout'))?> )
		</p>
	</div>
	<a href="<?php echo $this->url('user/index')?>" id="enter-ucenter">进入会员中心</a>
</div>