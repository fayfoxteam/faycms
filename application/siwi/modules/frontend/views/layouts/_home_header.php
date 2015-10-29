<?php
use fay\helpers\Html;
use fay\models\File;
?>
<header class="g-hd" id="g-hd">
	<div class="hd-bar-shadow"></div>
	<div class="hd-bar">
		<div class="hd-bar-logo">
			<a href="<?php echo $this->url()?>"><img src="<?php echo $this->appStatic('images/home-logo.png')?>" /></a>
		</div>
		<div class="hd-bar-user" id="g-hdu-links">
			<?php if(F::app()->session->get('avatar')){
				echo Html::img(F::app()->session->get('avatar'), 2);
			}?>
			<em class="arrow"></em>
			<ul class="sub">
			<?php if($this->current_user){?>
				<li><?php echo Html::link('发布作品', array('user/work/create'), array(
					'class'=>'logout-link',
					'title'=>false,
				))?></li>
				<li><?php echo Html::link('发布博文', array('user/post/create'), array(
					'class'=>'logout-link',
					'title'=>false,
				))?></li>
				<li><?php echo Html::link('上传素材', array('user/material/create'), array(
					'class'=>'logout-link',
					'title'=>false,
				))?></li>
				<li><?php echo Html::link('退出', array('user/logout'), array(
					'class'=>'logout-link',
					'title'=>false,
				))?></li>
			<?php }else{?>
				<li><?php echo Html::link('登陆', array('login-mini'), array(
					'class'=>'login-link',
					'title'=>false,
				))?></li>
				<li><?php echo Html::link('注册', array('register-mini'), array(
					'class'=>'register-link',
					'title'=>false,
				))?></li>
			<?php }?>
			</ul>
		</div>
	</div>
	<div class="uhd-info">
		<div class="uhd-avatar">
			<?php echo Html::img($user['avatar'], File::PIC_RESIZE, array(
				'spare'=>'avatar',
				'dw'=>178,
				'dh'=>178,
			))?>
			<div class="actions">
				<?php if($is_follow){
					echo Html::link('取消关注', 'javascript:;', array(
						'class'=>'follow-link',
						'data-followed'=>'1',
					));
				}else{
					echo Html::link('添加关注', 'javascript:;', array(
						'class'=>'follow-link',
						'data-followed'=>'0',
					));
				}?>
				|
				<?php echo Html::link('发送信息')?>
			</div>
		</div>
		<div class="info">
			<h1 class="u-name"><?php echo $user['nickname']?></h1>
			<div class="moreinfo">呵呵哈嘿</div>
		</div>
		<div class="atten">
			<a href="javascript:;"><em><?php echo $popularity?></em>人气</a>
			<a href="javascript:;"><em><?php echo $creativity?></em>创造力</a>
			<a href="javascript:;"><em><?php echo $fans?></em>粉丝</a>
			<a href="javascript:;"><em><?php echo $follow?></em>关注</a>
		</div>
	</div>
</header>