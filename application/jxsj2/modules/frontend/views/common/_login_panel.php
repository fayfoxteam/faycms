<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\User;
?>
<div class="box" id="login-panel">
	<form id="login-form" action="<?php echo $this->url('login')?>" method="post">
		<div class="box-content">
			<div class="st"><div class="sl"><div class="sr"><div class="sb">
				<div class="p16 clearfix">
					<h2>用户登录</h2>
					<?php if(F::app()->current_user){?>
					<?php $last_login = User::model()->getLastLoginInfo('login_time')?>
					<table>
						<tr>
							<th width="73">当前用户：</th>
							<td><?php echo F::session()->get('user.username')?></td>
						</tr>
						<tr>
							<th>上次登录：</th>
							<td><?php echo Date::format($last_login['login_time'])?></td>
						</tr>
						<tr>
							<td colspan="2"><?php 
								echo Html::link('前往考试', array('user/exam'));
								echo Html::link('退出登录', array('user/logout'), array(
									'style'=>'margin-left:10px;'
								));
							?></td>
						</tr>
					</table>
					<?php }else{?>
					<table>
						<tr>
							<th width="53">会&nbsp;&nbsp;&nbsp;员：</th>
							<td><?php echo F::form()->inputText('username', array(
								'class'=>'wp90',
							))?></td>
						</tr>
						<tr>
							<th>密&nbsp;&nbsp;&nbsp;码：</th>
							<td><?php echo F::form()->inputPassword('password', array(
								'class'=>'wp90',
							))?></td>
						</tr>
						<tr>
							<th>验证码：</th>
							<td><?php
								echo F::form()->inputText('vcode', array(
									'class'=>'wp40',
								));
								echo Html::img($this->url('file/vcode', array(
									'w'=>64,
									'h'=>23,
								)).'?', 1, array(
									'onClick'=>'this.src=this.src+Math.random()',
									'class'=>'vam ml10',
								));
							?></td>
						</tr>
						<tr>
							<th></th>
							<td><a href="javascript:;" class="btn-blue" id="login-form-submit">登录</a></td>
						</tr>
					</table>
					<?php }?>
				</div>
			</div></div></div></div>
		</div>
	</form>
</div>
<script>
$(function(){
	$('#login-form-submit').on('click', function(){
		$("#login-form").submit();
	});
	$("#login-form").on('keypress', ':text,:password', function(event){
		if(event.keyCode==13){
			$("#login-form").submit();
		}
	});
});
</script>