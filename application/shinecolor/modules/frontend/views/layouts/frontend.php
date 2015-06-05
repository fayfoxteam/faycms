<?php
use fay\models\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/jquery.fancybox-1.3.4.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.current_user = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<meta content="<?php if(isset($keywords))echo $keywords;?>" name="keywords" />
<meta content="<?php if(isset($description))echo $description;?>" name="description" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get(site.sitename)?></title>
</head>
<body>
<?php 
	include '_header.php';
	include '_sitenav.php';
	if(F::app()->uri->router != 'default/index/index'){
		include '_sub_banner.php';
	}
	if($breadcrumbs){
		include '_breadcrumbs.php';
	}
	echo $content;
	include '_footer.php';
	echo \F::app()->flash->get();
?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->staticFile('js/common.js')?>"></script>
<script>
$(function(){
	common.init();
})
</script>
</body>
</html>