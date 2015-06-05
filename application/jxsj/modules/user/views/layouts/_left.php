<?php
use fay\helpers\Html;
?>
<div class="w230 fl">
	<div class="box category-post">
		<div class="box-title">
			<h3>参加考试</h3>
		</div>
		<div class="box-content">
			<div class="st"><div class="sl"><div class="sr"><div class="sb">
				<div class="p16 clearfix">
					<ul>
						<li><?php echo Html::link('我的考卷', array('user/exam'))?></li>
						<li><?php echo Html::link('试卷列表', array('user/paper'))?></li>
					</ul>
				</div>
			</div></div></div></div>
		</div>
	</div>
	<div class="box category-post">
		<div class="box-title">
			<h3>会员信息</h3>
		</div>
		<div class="box-content">
			<div class="st"><div class="sl"><div class="sr"><div class="sb">
				<div class="p16 clearfix">
					<ul>
						<li><?php echo Html::link('个人资料', array(
							'user/profile'
						), array(
							'class'=>$current_directory == 'profile' ? 'crt' : '',
						))?></li>
						<li><?php echo Html::link('密码修改', array(
							'user/profile/password'
						), array(
							'class'=>$current_directory == 'password' ? 'crt' : '',
						))?></li>
						<li><?php echo Html::link('安全退出', array(
							'user/logout'
						))?></li>
					</ul>
				</div>
			</div></div></div></div>
		</div>
	</div>
</div>