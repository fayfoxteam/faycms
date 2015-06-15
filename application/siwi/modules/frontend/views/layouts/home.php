<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>static/siwi/css/style.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>static/siwi/css/home.css" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get(site.sitename)?></title>
</head>
<body>
<?php include '_home_header.php';?>
<div class="g-con">
	<div class="g-mn">
		<?php echo $content;?>
	</div>
</div>
<?php include '_footer.php';?>
</body>
</html>