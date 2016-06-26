<?php
use fay\services\Option;
use fay\helpers\Html;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"/>
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<title><?php if(!empty($title)){
	echo $title, '_';
}
echo Option::get('site:sitename')?></title>
</head>
<body>
<?php include '_site_nav.php'?>
<div class="main-bg">
<?php include '_header.php'?>
	<div class="cf g-con w1000">
		<?php echo $content?>
	</div>
</div>
<?php include '_footer.php'?>
<script type="text/javascript" src="<?php echo $this->appStatic('js/mail.js')?>"></script>
</body>
</html>