<?php
use fay\helpers\Html;
use fay\models\Option;

$menu = array(
	array(
		'name'=>'home',
		'link'=>$this->url(),
		'label'=>'首页',
	),
	array(
		'name'=>'blog',
		'link'=>$this->url('post'),
		'label'=>'博文',
	),
	array(
		'name'=>'work',
		'link'=>$this->url('work'),
		'label'=>'我的作品',
	),
	array(
		'name'=>'about',
		'link'=>$this->url('about'),
		'label'=>'关于我',
	),
);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/jquery.fancybox-1.3.4.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>" />
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->staticFile('js/menu.js')?>"></script>
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get('site.sitename')?></title>
</head>
<body>
<div class="wrapper">
	<div class="menu">
		<div class="menu-in">
			<div class="logo">
				<a href="<?php echo $this->url()?>" title="Fayfox"></a>
			</div>
			<ul class="menu-links">
			<?php foreach($menu as $m){?>
				<?php if(isset($current_directory) && $current_directory == $m['name']){?>
					<li class="menu-link-sel">
						<a href="<?php echo $m['link']?>"><?php echo $m['label']?></a>
					</li>
				<?php }else{?>
				<li>
					<a href="<?php echo $m['link']?>"><?php echo $m['label']?></a>
				</li>
				<?php }?>
			<?php }?>
			</ul>
		</div>
	</div>
	<div class="content-shade"></div>
	<div class="content">
		<?php echo $content?>
	</div>
	<div class="footer">
		<div class="footer-in">
			<div style="padding-left:70px;"><a href="http://www.chidiaoni.com">吃掉你</a></div>
			<div class="copyright">Copyright © 2012-2013 <a href="<?php echo $this->url()?>">fayfox.com</a></div>
			<div class="beian">浙ICP备12036784号-1</div>
		</div>
	</div>
	<?php if(!empty($qr_data)){?>
	<div id="qrcode">
		<h5>手机访问</h5>
		<img src="<?php echo $this->url('file/qrcode', array(
			'data'=>base64_encode($qr_data),
		))?>" />
	</div>
	<?php }?>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery.animate-shadow-min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript">
$(function(){
	$(".menu-links").menu();
	
	$(".post-list .post-list-item").live("mouseover", function(){
		$(this).stop().animate({
			"box-shadow" : "5px 5px 10px rgba(0, 0, 0, 0.25)",
			"borderBottomRightRadius" : "30"
		});
	});
	$(".post-list .post-list-item").live("mouseout", function(){
		$(this).stop().animate({
			"box-shadow": "2px 2px 10px #E7E5E6",
			"borderBottomRightRadius" : "0"
		});
	});

	//下拉后顶部固定
	$(".fixed-content").each(function(){
		var o = this;
		var timeout = null;
		var offset_top = $(o).offset().top;
		$(o).css({
			"width": $(o).width()
		});
		$(window).scroll(function(){
			clearTimeout(timeout);
			if($("body").get(0).getBoundingClientRect().top <= -(offset_top + 10)){
				if(!$(o).next(".fix-content-succedaneum").length){
					$(o).after('<div class="fix-content-succedaneum" style="width:'+$(o).width()+'px;height:'+$(o).height()+'px;"></div>');
				}
				if($.browser.msie && $.browser.version < 8){
					timeout = setTimeout(function(){
						$(o).css({
							"position": "absolute",
							"z-index": 1000,
							"left": $(o).offset().left
						})
						.animate({
							"top": - $("body").get(0).getBoundingClientRect().top
						})
						.addClass("col-fixed");
					}, 200);
				}else{
					$(o).css({
						"position": "fixed",
						"top": 0,
						"z-index": 1000,
						"left": $(o).offset().left
					})
					.addClass("col-fixed");
				}
			}else{
				$(o).css({
					"position": "relative",
					"top": 0,
					"left": 0
				})
				.removeClass("col-fixed");
				$(o).next(".fix-content-succedaneum").remove();
			}
		});
	});
	$(".work-file-item a").fancybox({
		'transitionIn' : 'elastic',
		'transitionOut' : 'elastic',
		'type' : 'image',
		'padding' : 0
	});
});
</script>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/analyst.min.js"></script>
<script>_fa.init();</script>
</body>
</html>