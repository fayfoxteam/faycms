<?php
use fay\models\Option;
use fay\helpers\Html;
use fay\models\File;
use fay\models\User;
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/login.css')?>" />
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prefixfree.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
</script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo $this->assets('js/DD_belatedPNG_0.0.8a-min.js')?>"></script>
<script>
DD_belatedPNG.fix('fieldset,.ring');
</script>
<![endif]-->
<title><?php echo Option::get('site:sitename')?>后台登陆</title>
</head>
<body>
<div class="main">
	<div class="left">
		<div class="rings">
			<div class="ring ring-0"></div>
			<div class="ring ring-1"></div>
			<div class="ring ring-2"></div>
			<div class="ring ring-3"></div>
		</div>
		<div class="items">
			<div class="item-1">
				<div class="act">
					<span class="big-circle circle bl">
						<span class="the-circle circle"></span>
						<strong><?php echo date('Y')?></strong>
						<small>年</small>
					</span>
					<span class="small-circle s1 circle bl">
						<span class="the-circle circle"></span>
						<strong><?php echo date('m')?></strong>
						<small>月</small>
					</span>
					<span class="small-circle s2 circle bl">
						<span class="the-circle circle"></span>
						<strong><?php echo date('d')?></strong>
						<small>日</small>
					</span>
				</div>
				<div class="preview hide">
					<span class="middle-circle circle bl">
						<span class="the-circle circle bl">
							<span class="text"></span>
						</span>
					</span>
				</div>
			</div>
			<div class="item-2">
				<div class="act hide">
					<span class="big-circle circle bl">
						<span class="the-circle circle"></span>
						<span class="text">
							<strong>浏览器</strong>
							<small></small>
						</span>
					</span>
				</div>
				<div class="preview">
					<span class="middle-circle circle bl">
						<span class="the-circle circle bl">
							<span class="text"></span>
						</span>
					</span>
				</div>
			</div>
			<div class="item-3">
				<div class="act hide">
					<span class="big-circle circle bl">
						<span class="the-circle circle"></span>
						<span class="text">
							<strong>版权所有</strong>
							<small>Copyright</small>
						</span>
					</span>
				</div>
				<div class="preview">
					<span class="middle-circle circle bl">
						<span class="the-circle circle bl">
							<span class="text">版权</span>
						</span>
					</span>
				</div>
			</div>
		</div>
		<div class="contents">
			<div class="content-1"></div>
			<div class="content-2 hide">
				<p><label>内核：</label><span class="browser-core"></span></p>
				<p><label>内核版本：</label><span class="browser-core-version"></span></p>
				<p><label>套壳：</label><span class="browser-shell"></span></p>
				<p><label>来源：</label><span class="browser-from"><?php echo $iplocation->getCountryAndArea(F::app()->ip);?></span></p>
			</div>
			<div class="content-3 hide">
				<p>QQ:369281831</p>
				<p>E-mail:<a href="mailto:admin@fayfox.com">admin@fayfox.com</a></p>
				<p>© <a href="http://www.fayfox.com">fayfox.com</a></p>
				<p>2011-<?php echo date('Y')?></p>
			</div>
		</div>
	</div>
	<?php if(\F::app()->current_user){?>
		<?php $user = User::model()->get(\F::app()->current_user, 'avatar,username')?>
		<div class="right top-to-bottom">
			<div class="login-form-container">
				<fieldset class="logo">Faycms</fieldset>
				<fieldset class="user-info">
					<div class="user-avatar">
						<?php echo Html::img($user['user']['avatar'], File::PIC_THUMBNAIL, array(
							'spare'=>'avatar',
						))?>
					</div>
					<div class="user-profile">
						您好，<?php echo $user['user']['username']?>
						<?php echo Html::link('更换账号登录', array('admin/login/logout'), array(
							'class'=>'logout-link',
						));?>
					</div>
				</fieldset>
				<fieldset>
					<a href="<?php echo $this->url('admin/index/index')?>" id="login-form-submit">进&nbsp;入&nbsp;后&nbsp;台</a>
				</fieldset>
			</div>
		</div>
	<?php }else{?>
		<div class="right <?php if(isset($error)){
			echo 'shake';
		}else{
			echo 'top-to-bottom';
		}?>">
			<div class="login-form-container">
				<form method="post" id="login-form">
					<fieldset class="logo">Faycms</fieldset>
					<div class="error-msg"><?php if(isset($error))echo $error;?></div>
					<fieldset class="input-container username">
						<?php echo F::form()->inputText('username', array(
							'placeholder'=>'用户名',
						))?>
					</fieldset>
					<fieldset class="input-container password">
						<?php echo F::form()->inputPassword('password', array(
							'placeholder'=>'密码',
						))?>
					</fieldset>
					<fieldset>
						<a href="javascript:;" id="login-form-submit">登&nbsp;&nbsp;录</a>
					</fieldset>
				</form>
			</div>
		</div>
	<?php }?>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>
_fa.init();
var login = {
	'input':function(){
		$('.right').on('focus', 'input', function(){
			$(this).parent().addClass('active').siblings().removeClass('active');
		}).on('blur', 'input', function(){
			$(this).parent().removeClass('active');
		});
	},
	'timer':function(){
		var date = new Date();
		var hour = date.getHours();
		if(hour < 10) hour = '0' + hour;
		var minute = date.getMinutes();
		if(minute < 10) minute = '0' + minute;
		var second = date.getSeconds();
		if(second < 10) second = '0' + second;
		$('.contents .content-1').text(hour + ':' + minute + ':' + second);
		$('.item-1 .preview .text').text(hour + ':' + minute);
	},
	'browser':function(){
		var browser = _fa.getBrowser();
		if(browser[0] == 'IE'){
			$('.items .item-2 .preview .text').text(browser[0] + ' ' + browser[1]);
			$('.items .item-2 .act small').text(browser[0] + ' ' + browser[1]);
		}else{
			$('.items .item-2 .preview .text').text(browser[0]);
			$('.items .item-2 .act small').text(browser[0]);
		}
		$('.browser-core').text(browser[0]);
		$('.browser-core-version').text(browser[1]);
		$('.browser-shell').text(browser[2] ? browser[2] : '无');
		$('.browser-core').text(browser[0]);
	},
	'form':function(){
		$('#login-form').on('click', '#login-form-submit', function(){
			$('#login-form').submit();
			return false;
		}).on('keyup', function(event){
			if(event.keyCode == 13 || event.keyCode == 108){
				$('#login-form').submit();
				return false;
			}
		}).on('submit', function(){
			if($(this).find('[name="username"]').val() == ''){
				$(this).find('.error-msg').text('用户名不能为空');
				login.rightShake();
				return false;
			}else if($(this).find('[name="password"]').val() == ''){
				$(this).find('.error-msg').text('密码不能为空');
				login.rightShake();
				return false;
			}
		});
	},
	//登录框左右抖动
	'rightShake':function(){
		$('.right').addClass('shake');
		setTimeout(function(){
			$('.right').removeClass('shake');
		}, 1000);
	},
	'focus':function(){
		$('#login-form').on('click', '.input-container', function(){
			$(this).find('input').focus();
		});
	},
	'items':function(){
		//IE8, IE9不支持css3，需要用js控制显示隐藏
		$('.items').on('click', '.item-1', function(){
			$('.item-1').find('.preview').hide()
				.prev('.act').show();
			$('.item-2,.item-3').find('.preview').show()
				.prev('.act').hide();

			$('.content-1').fadeIn().siblings().fadeOut();
			$('.items').addClass('time').removeClass('browser copyright');
			setTimeout(function(){
				$('.items').css({
					'transform':'rotateZ(0)'
				});
			}, 1000);
		}).on('click', '.item-2', function(){
			$('.item-2').find('.preview').hide()
				.prev('.act').show();
			$('.item-1,.item-3').find('.preview').show()
				.prev('.act').hide();

			$('.content-2').fadeIn().siblings().fadeOut();
			$('.items').addClass('browser').removeClass('time copyright');
			setTimeout(function(){
				$('.items').css({
					'transform':'rotateZ(120deg)'
				});
			}, 1000);
		}).on('click', '.item-3', function(){
			$('.item-3').find('.preview').hide()
				.prev('.act').show();
			$('.item-1,.item-2').find('.preview').show()
				.prev('.act').hide();

			$('.content-3').fadeIn().siblings().fadeOut();
			$('.items').addClass('copyright').removeClass('browser time');
			setTimeout(function(){
				$('.items').css({
					'transform':'rotateZ(-120deg)'
				});
			}, 1000);
		});
	},
	'init':function(){
		this.input();
		this.timer();
		this.browser();
		this.form();
		this.focus();
		this.items();
		setTimeout(function(){
			$('.right').removeClass('shake right-to-left');
		}, 1000);
	}
};
$(function(){
	login.init();
	setInterval(login.timer, 1000);
});
</script>
</body>
</html>