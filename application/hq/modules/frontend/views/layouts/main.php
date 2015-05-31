<?php
$css_url = $this->staticFile('css');
$js_url = $this->staticFile('js');
$img_url = $this->staticFile('images');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>首页</title>

    <link href="<?= $css_url ?>/base.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/index.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/util.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= $this->url('js/jquery-1.8.3.min.js') ?>"></script>
    <script type="text/javascript" src="<?= $js_url ?>/focux.js"></script>

    
</head>

<body>

<?php include '_header.php' ?>

<?php echo $content ?>

<?php include '_footer.php' ?>

<script src="<?= $this->staticFile('js/Msclass.js') ?>"></script>

<script>
	new Marquee("gg",0,1,345,200,100,0,1000,120,0);
	// new Marquee(
	// 	{
	// 		MSClass	  : ["slider_box","contentList","previewList"],
	// 		Direction : 2,
	// 		Step	  : 0.3,
	// 		Width	  : 337,
	// 		Height	  : 200,
	// 		Timer	  : 20,
	// 		DelayTime : 3500,
	// 		AutoStart : true
	// 	});
jQuery(".slider_wrap").slide({mainCell:"#slider_box ul",autoPlay:true});
</script>
</body>
</html>
