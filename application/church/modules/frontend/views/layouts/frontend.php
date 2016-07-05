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
			echo $title, '|';
		}
		echo Option::get('site:sitename')?></title>
	<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
	<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
	<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
		system.user_id = '<?php echo \F::app()->current_user?>';
	</script>
</head>
<body>
<div class="wrapper">
	<header>
		<nav class="top"></nav>
		<nav class="main-menu">
			<div class="container">
				<div class="row">
					<div class="col-md-5"></div>
					<div class="col-md-7">
						<ul class="main-menu">
							<li><a href="<?php echo $this->url()?>">Home</a></li>
							<li><a href="<?php echo $this->url()?>">Home</a></li>
							<li><a href="<?php echo $this->url()?>">Home</a></li>
							<li><a href="<?php echo $this->url()?>">Home</a></li>
							<li><a href="<?php echo $this->url()?>">Home</a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<div class="banner">
		
	</div>
	<div class="container">
		<div class="row">
			<main class="col-md-8 post-list">
				<article>
					<div class="post-content">
						
					</div>
					<div class="post-content">
						<h2 class="post-title">带轮播图文章</h2>
						<div class="post-meta">
							<span class="post-meta-category">分类1</span>
							<span class="post-meta-views">
								<span>阅读数</span>
								<a href="">18</a>
							</span>
						</div>
						<div class="post-description">
							<p>这是一段简介</p>
							<a href="" class="btn btn-lg btn-blue">阅读全文</a>
						</div>
					</div>
				</article>
			</main>
			<aside class="col-md-4 widget-area">
				<div class="widget">
					<h5 class="widget-title">小工具标题</h5>
				</div>
				<div class="widget">
					<h5 class="widget-title">小工具标题</h5>
				</div>
			</aside>
		</div>
	</div>
</div>
</body>
</html>