<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div id="index-slide">
	<?php \F::app()->widget->load('index-slides-camera')?>
</div>
<div class="g-con">
	<div class="g-mn">
		<section class="clearfix showcase">
			<article class="case-1">
				<div class="inner">
					<figure>
						<img src="<?php echo $this->appStatic('images/icon1.png')?>" />
					</figure>
					<h3><?php echo $case_1['title']?></h3>
					<div class="item-introtext">
						<?php echo $case_1['abstract']?>
					</div>
					<?php echo Html::link('查看详细', array(
						'page/'.$case_1['id'],
					), array(
						'class'=>'more',
					))?>
				</div>
			</article>
			<article class="case-2">
				<div class="inner">
					<figure>
						<img src="<?php echo $this->appStatic('images/icon2.png')?>" />
					</figure>
					<h3><?php echo $case_2['title']?></h3>
					<div class="item-introtext">
						<?php echo $case_2['abstract']?>
					</div>
					<?php echo Html::link('查看详细', array(
						'page/'.$case_2['id'],
					), array(
						'class'=>'more',
					))?>
				</div>
			</article>
			<article class="case-3">
				<div class="inner">
					<figure>
						<img src="<?php echo $this->appStatic('images/icon3.png')?>" />
					</figure>
					<h3><?php echo $case_3['title']?></h3>
					<div class="item-introtext">
						<?php echo $case_3['abstract']?>
					</div>
					<?php echo Html::link('查看详细', array(
						'page/'.$case_3['id'],
					), array(
						'class'=>'more',
					))?>
				</div>
			</article>
		</section>
		<section class="clearfix story">
			<h3 class="sec-title"><span>我们的故事</span></h3>
			<div class="clearfix item-content">
				<figure>
					<?php echo Html::img($about['thumbnail'], File::PIC_ORIGINAL, array(
						'width'=>false,
						'height'=>false,
					))?>
				</figure>
				<div class="item-introtext"><?php echo $about['content']?></div>
			</div>
		</section>
		<section class="clearfix product">
			<h3 class="sec-title"><span>我们的产品</span></h3>
			<div class="clearfix item-content">
				<ul class="clearfix" id="product-list">
				<?php foreach($products as $p){?>
					<li><a href="<?php echo $this->url('product/'.$p['id'])?>"
						title="<?php echo Html::encode($p['title'])?>"><?php echo Html::img($p['thumbnail'], File::PIC_RESIZE, array(
						'dw'=>268,
						'dh'=>242,
					), array(
						'alt'=>Html::encode($p['title']),
					))?>
						<span class="zoom-bg"></span>
						<span class="zoom-icon"></span>
					</a></li>
				<?php }?>
				</ul>
			</div>
			<div class="nav">
				<a href="javascript:;" id="product-list-prev">&lt;</a>
				<a href="javascript:;" id="product-list-next">&gt;</a>
			</div>
		</section>
	</div>
</div>
<script src="<?php echo $this->assets('js/jquery.carouFredSel-6.2.1-packed.js')?>"></script>
<script src="<?php echo $this->assets('js/jquery.touchSwipe.min.js')?>"></script>
<script>
var app = {
	'productList':function(){
		$('#product-list').carouFredSel({
			responsive: true,
			width: '100%',
			items: {
				width: 280,
				height: 'variable',
				visible: {
					min: 2,
					max: 4
				},
				minimum: 1
			},
			scroll: {
				items: 1,
				fx: "scroll",
				easing: "swing",
				duration: 500,
				queue: true
			},
			auto: false,
			next: "#product-list-prev",
			prev: "#product-list-next",
			swipe:{
				onTouch: true
			}
		});
	},
	'init':function(){
		this.productList();
	}
};
$(function(){
	app.init();
})
</script>