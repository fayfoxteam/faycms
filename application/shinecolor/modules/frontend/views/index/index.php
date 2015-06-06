<?php
use fay\helpers\Html;
use fay\models\Post;
?>
<div id="index-slide">
	<?php \F::app()->widget->load('index-slides-camera')?>
</div>
<section id="index-abstract" class="w1000">
	<?php \F::app()->widget->load('index-abstract')?>
</section>
<section id="main-business">
	<header>
		<span class="cn">主营业务</span>
		<span class="en">The Main Business</span>
	</header>
	<div class="business-content">
		<div class="w1000">
			<?php $widget_data = F::app()->widget->getData('business');?>
			<nav class="introduce">
				<ul>
				<?php foreach($widget_data['data'] as $k=>$w){?>
					<li class="<?php if($k == 0){
							echo 'first';
						}?>"><a href="javascript:;" class="<?php if($k == 0){
							echo 'current';
						}?> goto-<?php echo $k+1?>"><span><?php echo $w['key']?></span></a>
					</li>
				<?php }?>
				</ul>
			</nav>
			<div class="introduce-content">
				<ul>
				<?php foreach($widget_data['data'] as $k=>$w){?>
					<li><?php echo $w['value']?></li>
				<?php }?>
				</ul>
			</div>
		</div>
	</div>
</section>
<section id="news-and-works">
	<div class="w1000 clearfix">
		<div class="fl news">
			<header>
				<span class="cn">新闻</span>
				<span class="en">News</span>
			</header>
			<ul class="news-list">
			<?php foreach($news as $n){?>
				<li class="disc">
					<a href="<?php echo Post::model()->getLink($n, 'news')?>">
						<time class="fr"><?php echo date('Y-m-d', $n['publish_time'])?></time>
						<span><?php echo Html::encode($n['title'])?></span>
					</a>
				</li>
			<?php }?>
			</ul>
		</div>
		<div class="fr works">
			<header>
				<span class="cn">我们的产品</span>
				<span class="en">Products</span>
			</header>
			<div class="work-list">
			<?php 
				$left_products = array_slice($products, 0, intval(count($products) / 2));
				$right_products = array_slice($products, ceil(count($products) / 2));
			?>
				<div class="fl left">
					<ul>
					<?php foreach($left_products as $p){?>
						<li>
							<?php echo Html::link(Html::img($p['thumbnail'], 4, array(
								'dw'=>243,
								'dh'=>185,
								'alt'=>Html::encode($p['title']),
							)), array('product/'.$p['id']), array(
								'encode'=>false,
								'title'=>Html::encode($p['title']),
							))?>
						</li>
					<?php }?>
					</ul>
					<a href="javascript:;" class="next"></a>
				</div>
				<div class="fr right">
					<ul>
					<?php foreach($right_products as $p){?>
						<li>
							<?php echo Html::link(Html::img($p['thumbnail'], 4, array(
								'dw'=>243,
								'dh'=>185,
								'alt'=>Html::encode($p['title']),
							)), array('product/'.$p['id']), array(
								'encode'=>false,
								'title'=>Html::encode($p['title']),
							))?>
						</li>
					<?php }?>
					</ul>
					<a href="javascript:;" class="prev"></a>
				</div>
			</div>
		</div>
	</div>
</section>
<section id="our-partners">
	<div class="w1000 clearfix">
		<img src="<?php echo $this->staticFile('images/our-partners.png')?>" />
	</div>
</section>
<script src="<?php echo $this->url()?>js/jcarousellite_1.0.1.min.js"></script>
<script>
var app = {
	'introduct':function(){
		$(".introduce a").click(function(){
			$(".introduce a").removeClass("current");
			$(this).addClass("current");
			return false;
		});

		$(".introduce-content").jCarouselLite({
			'speed':500,
			'visible':1,
			'btnGo':['.goto-1', '.goto-2', '.goto-3', '.goto-4', '.goto-5', '.goto-6']
		});
	},
	'workList':function(){
		$("#news-and-works .work-list .left").jCarouselLite({
			'vertical':true,
			'speed':1000,
			'visible':2,
			'btnNext': "#news-and-works .work-list .left .next"
		});

		$("#news-and-works .work-list .right").jCarouselLite({
			'vertical':true,
			'speed':1000,
			'visible':2,
			'btnPrev': "#news-and-works .work-list .right .prev" 
		});
		
		var work_list = setInterval(function(){
			$("#news-and-works .work-list .right .prev").click();
			$("#news-and-works .work-list .left .next").click();
		}, 4000);

		$("#news-and-works .work-list").mouseover(function(){
			clearInterval(work_list);
		}).mouseout(function(){
			work_list = setInterval(function(){
				$("#news-and-works .work-list .right .prev").click();
				$("#news-and-works .work-list .left .next").click();
			}, 4000);
		});
	},
	'init':function(){
		this.introduct();
		this.workList();
	}
};
$(function(){
	app.init();
})
</script>