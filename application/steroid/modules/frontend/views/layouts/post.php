<?php
use fay\services\Option;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php if(!empty($canonical)){?>
		<link rel="canonical" href="<?php echo $canonical?>" />
	<?php }?>
	<title><?php if(!empty($title)){
			echo $title, ' | ';
		}
		echo Option::get('site:sitename')?></title>
	<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
	<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/post.css')?>" >
	<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-2.2.4.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
	</script>
</head>
<body>
<div class="wrapper">
	<header class="blog-header">
		<a href="<?php echo F::app()->view->url()?>" class="return-to-site">
			<i class="fa fa-angle-left"></i>
			Return to site
		</a>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="post-title">
						<h1>RAD 140 (Testolone) - SARM Powder</h1>
						<h2>Buy SARMs - RAD-140 (a.k.a testolone) Powder Online</h2>
					</div>
				</div>
			</div>
		</div>
	</header>
	<?php echo $content?>
	<div class="container blog-footer">
		<div class="row">
			<div class="col-md-6">
				<a href="" class=" return-to-site">
					<i class="fa fa-angle-left"></i>
					Return to site
				</a>
			</div>
			<div class="col-md-6">
				<span class="copy-right">© 2015 - pharmrich.com</span>
			</div>
		</div>
	</div>
</div>
</body>
</html>