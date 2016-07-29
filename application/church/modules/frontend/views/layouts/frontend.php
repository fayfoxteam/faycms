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
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
	<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-2.2.4.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
		system.user_id = '<?php echo \F::app()->current_user?>';
	</script>
</head>
<body>
<div class="wrapper">
	<header class="page-header">
		<div class="container">
			<div class="row">
				<div class="col-md-5 logo-container">
					<a href="<?php echo $this->url()?>"><?php
						$logo = Option::get('site:logo');
						if($logo){
							echo Html::img($logo);
						}else{
							echo Html::img($this->appStatic('images/logo.png'));
						}
					?></a>
				</div>
				<nav class="col-md-7 main-menu">
					<?php F::widget()->load('main-menu')?>
				</nav>
			</div>
		</div>
	</header>
	<div class="mobile-page-header">
		<div class="container">
			<div class="row">
				<div class="logo">
					<a href=""><img src="<?php echo $this->appStatic('images/logo.png')?>" /></a>
				</div>
				<a href="javascript:;" class="toggle-mobile-menu"></a>
			</div>
		</div>
		<div class="mobile-menu-container">
			<nav class="mobile-menu">
				<?php F::widget()->load('mobile-menu')?>
			</nav>
		</div>
		<div class="mask"></div>
	</div>
	<?php F::widget()->load('banner')?>
	<div class="container page-content">
		<div class="row">
			<main class="col-md-8 post-list">
				<article>
					<div class="post-featured">
						<div class="swiper-container post-files">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<img src="http://55.fayfox.com/fayfox/uploads/church/widget/2016/07/YDnvV.jpg" />
								</div>
								<div class="swiper-slide">
									<img src="http://55.fayfox.com/fayfox/uploads/church/widget/2016/07/mFrEk.jpg" />
								</div>
								<div class="swiper-slide">
									<img src="http://55.fayfox.com/fayfox/uploads/church/widget/2016/07/7dLBg.jpg" />
								</div>
							</div>
							<div class="swiper-pagination"></div>
							<div class="swiper-control-container">
								<a class="swiper-btn-prev"></a>
								<a class="swiper-btn-next"></a>
							</div>
						</div>
					</div>
					<div class="post-content">
						<h2 class="post-title">
							<a href="">带轮播图文章</a>
						</h2>
						<div class="post-meta">
							<time class="post-meta-item post-meta-time">2天前</time>
							<a href="" class="post-meta-item post-meta-category">分类1</a>
							<span class="post-meta-item post-meta-views">
								<span>阅读数</span>
								<a href="">18</a>
							</span>
						</div>
						<div class="post-description">
							<p>Duis auctor arcu ac mi bibendum posuere. Integer diam orci, faucibus ut mi sed, tincidunt vehicula erat. Sed ultricies tempor nunc, nec malesuada tortor vehicula ac. Curabitur imperdiet massa ac ex pretium, et mollis metus aliquet. Phasellus tempor nunc et odio interdum iaculis. Vestibulum ac pretium erat. Maecenas sollicitudin sagittis...</p>
							<a href="" class="btn btn-lg btn-blue">阅读全文</a>
						</div>
					</div>
				</article>
				<article>
					<div class="post-featured">
						<div class="post-thumb">
							<a href=""><img src="http://55.fayfox.com/fayfox/uploads/church/widget/2016/07/dTNBH.jpg" width="770" height="448" /></a>
						</div>
					</div>
					<div class="post-content">
						<h2 class="post-title">
							<a href="">带缩略图文章</a>
						</h2>
						<div class="post-meta">
							<time class="post-meta-item post-meta-time">2天前</time>
							<a href="" class="post-meta-item post-meta-category">分类1</a>
							<span class="post-meta-item post-meta-views">
								<span>阅读数</span>
								<a href="">18</a>
							</span>
						</div>
						<div class="post-description">
							<p>Duis auctor arcu ac mi bibendum posuere. Integer diam orci, faucibus ut mi sed, tincidunt vehicula erat. Sed ultricies tempor nunc, nec malesuada tortor vehicula ac. Curabitur imperdiet massa ac ex pretium, et mollis metus aliquet. Phasellus tempor nunc et odio interdum iaculis. Vestibulum ac pretium erat. Maecenas sollicitudin sagittis...</p>
							<a href="" class="btn btn-lg btn-blue">阅读全文</a>
						</div>
					</div>
				</article>
				<nav class="pager">
					<a href="" class="prev"></a>
					<a href="" class="">1</a>
					<span class="current">2</span>
					<span>...</span>
					<a href="" class="">3</a>
					<a href="" class="next"></a>
				</nav>
			</main>
			<aside class="col-md-4">
				<div class="widget-area">
					<?php F::widget()->area('index-sidebar')?>
					<div class="widget">
						<h5 class="widget-title">热门文章</h5>
						<article>
							<div class="post-thumb">
								<a href=""><img src="http://55.fayfox.com/fayfox/file/pic/f/10000?t=4&dw=60&dh=60" /></a>
							</div>
							<div class="post-content">
								<h5 class="post-title">
									<a href="">这是一个文章标题</a>
								</h5>
								<div class="post-meta">
									<span class="post-meta-category">分类1</span>
									<time class="post-meta-time">3天前</time>
								</div>
							</div>
						</article>
						<article>
							<div class="post-thumb">
								<a href=""><img src="http://55.fayfox.com/fayfox/file/pic/f/10000?t=4&dw=60&dh=60" /></a>
							</div>
							<div class="post-content">
								<h5 class="post-title">
									<a href="">这是一个文章标题</a>
								</h5>
								<div class="post-meta">
									<span class="post-meta-category">分类1</span>
									<time class="post-meta-time">3天前</time>
								</div>
							</div>
						</article>
					</div>
				</div>
			</aside>
		</div>
	</div>
	<footer class="page-footer">
		<div class="container">
			<div class="row">
				<aside class="col-md-3 col-sm-6">
					<h4>关于我们</h4>
					<div>
						<p>We welcome visitors at NewLife Church and would love to have you join us in church this weekend.</p>
						<p><b>E: info@yoursite.com</b><br>
							<b>L: <a href="#">Google Map</a></b></p>
					</div>
				</aside>
				<?php F::widget()->area('footer')?>
			</div>
		</div>
	</footer>
	<div class="page-copyright">
		<span><?php echo Option::get('site:copyright')?></span>
	</div>
</div>
<a href="#" class="scroll-to-top" title="回到顶部"></a>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<script>
$(function(){
	common.init();
});
</script>
</body>
</html>