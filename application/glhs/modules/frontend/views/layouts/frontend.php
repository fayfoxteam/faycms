<?php
use fay\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link type="image/x-icon" href="<?php echo $this->appStatic('favicon.ico')?>" rel="shortcut icon" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<title><?php if(!empty($title)){
	echo $title, '_';
}
echo OptionService::get('site:sitename')?></title>
<meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.camera.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<style>
<?php
$footer_image = F::widget()->getData('footer-background-image');
if(!empty($footer_image['src'])){
echo '.g-footer{background-image:url('.$footer_image['src'].')}';
}?>
</style>
</head>
<body>
<div class="wrap">
	<header class="g-top">
		<div class="container">
			<nav class="top-nav fr">
				<ul>
					<li><?php
						echo HtmlHelper::link('官方店铺', OptionService::get('site:weibo'), array(
							'target'=>'_blank',
						));
					?></li>
					<li><?php
						echo HtmlHelper::link('在线咨询', 'http://wpa.qq.com/msgrd?v=3&uin='.OptionService::get('site:qq').'&site=qq&menu=yes', array(
							'target'=>'_blank',
						));
					?></li>
					<li><?php
						echo HtmlHelper::link('联系我们', array('contact'));
					?></li>
					<li class="phone-container"><span class="phone-number" style="display:none"><?php echo OptionService::get('site:phone')?></span><span class="phone"></span></li>
				</ul>
			</nav>
		</div>
	</header>
	<div class="slider mb30 cf">
		<?php F::widget()->load('slider')?>
	</div>
	<div class="g-search mb30">
		<h4>搜索 SEARCH</h4>
		<form id="search-form" action="<?php echo $this->url('search')?>"><?php
			echo HtmlHelper::inputText('keywords', F::input()->get('keywords', 'trim'), array(
				'placeholder'=>'输入关键词',
				'id'=>'keywords',
			));
			echo HtmlHelper::link('', 'javascript:;', array(
				'id'=>'search-form-submit',
			));
		?></form>
	</div>
	<nav class="g-nav mb30">
		<div class="container">
			<ul class="cf"><?php
				echo HtmlHelper::link('网站首页', array(), array(
					'wrapper'=>'li',
				));
				echo HtmlHelper::link('关于我们', array('about'), array(
					'wrapper'=>'li',
				));
				foreach($cats as $c){
					if(!$c['is_nav'])continue;
					echo HtmlHelper::link($c['title'], array($c['alias'] ? $c['alias'] : $c['id']), array(
						'wrapper'=>'li',
					));
				}
			?></ul>
		</div>
	</nav>
	<?php echo $content?>
	<footer class="g-footer">
	<?php
		echo HtmlHelper::link('在线留言', 'http://wpa.qq.com/msgrd?v=3&uin='.OptionService::get('site:qq').'&site=qq&menu=yes', array(
			'target'=>'_blank',
			'class'=>'message-link',
		));
		echo HtmlHelper::link('联系我们', array('contact'), array(
			'class'=>'contact-link',
		));
	?>
	</footer>
</div>
<script>
$(function(){
	$('#search-form-submit').on('click', function(){
		window.location.href = system.url('search/'+$('#keywords').val());
		return false;
	});

	var phone_width = $('.phone-container .phone-number').show().width();
	$('.phone-container .phone-number').css({
		'width':0,
		'padding-left':0,
		'padding-right':0
	});
	$('.phone-container').on('mouseenter', function(){
		$('.phone-container .phone-number').animate({
			'width':phone_width,
			'padding-left':16,
			'padding-right':16
		});
	}).on('mouseleave', function(){
		$('.phone-container .phone-number').animate({
			'width':0,
			'padding-left':0,
			'padding-right':0
		});
	});
	
});
</script>
</body>
</html>