<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<meta content="<?php echo isset($keywords)? $keywords : ''?>" name="keywords" />
<meta content="<?php echo isset($description)? $description : ''?>" name="description" />
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<!--[if IE 6]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/DD_belatedPNG_0.0.8a-min.js"></script>
<![endif]-->
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get('site.sitename')?></title>
</head>
<body>
<div class="wrapper" id="inner-page">
	<?php include '_header.php'?>
	<div id="content">
		<aside id="sidebar">
			<div id="sidebar-bg-left">
				<div id="sidebar-bg-right">
					<div id="sidebar-content" class="fixed-content">
						<ul>
						<?php foreach($submenu as $s){?>
							<li>
								<?php echo Html::link($s['title'], $s['link'], array(
									'class'=>isset($s['class']) ? $s['class'] : false,
								))?>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
			</div>
		</aside>
		<div id="main-content">
			<h3 id="sub-title"><?php echo $subtitle?></h3>
			<div id="breadcrumbs">
				您现在的位置：
				<?php foreach($breadcrumbs as $k=>$b){
					if($k)echo '&gt;&nbsp;';
					if(!empty($b['link'])){
						echo "<a href='{$b['link']}'>{$b['title']}</a>";
					}else{
						echo $b['title'];
					}
				}?>
			</div>
			<?php if(!empty($banner)){?>
				<div id="sub-banner"><img src="<?php echo $this->staticFile('images/'.$banner)?>" height="145" /></div>
			<?php }?>
			<?php echo $content;?>
		</div>
		<div class="clear"></div>
	</div>
	<?php include '_footer.php'?>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery.masonry.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/fayfox.fixcontent.js"></script>
<script>
$(".titlediv").each(function(i){
	if($(this).find("input").val() != ""){
		$(this).find(".title-prompt-text").hide();
	}
});

$(".titlediv input").focus(function(){
	$(this).parent().find(".title-prompt-text").hide();
}).blur(function(){
	if($(this).val()==""){
		$(this).parent().find(".title-prompt-text").show();
	}
});

$("#content").imagesLoaded(function(){
	if($("#content").height() < 400){
		$("#content").height(400);
		$("#main-content").height(400);
	}
	$("#sidebar").height($("#content").height() - 12);
});

$(".fixed-content").fixcontent();

if($.browser.msie && $.browser.version == 6){
	DD_belatedPNG.fix(".fixpng");
}

$("a[href^='#']").click(function(){
	if($(this).attr("href").length > 1){
		$("html,body").animate({scrollTop: $($(this).attr("href")).offset().top - 10}, 500);
		return false;
	}
});
</script>
</body>
</html>