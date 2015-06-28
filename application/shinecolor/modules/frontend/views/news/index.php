<?php
use fay\helpers\Html;
?>
<div class="w1000 clearfix col-2">
	<div class="col-2-left">
		<nav class="left-menu">
			<ul>
				<li><a href="<?php echo $this->url('news')?>">新闻中心</a></li>
				<?php foreach($children as $c){?>
				<li><a href="<?php echo $this->url('news/'.$c['alias'])?>"><?php echo Html::encode($c['title'])?></a></li>
				<?php }?>
			</ul>
		</nav>
	</div>
	<div class="col-2-right">
		<div class="page-item">
			<header>
				<span class="title"><?php echo Html::encode($cat['title'])?></span>
				<span class="dashed"></span>
			</header>
			<div class="content">
				<section>
					<ul id="news-list">
						<?php $listview->showData()?>
					</ul>
				</section>
				<?php $listview->showPager()?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $this->url()?>faycms/js/fayfox.fixcontent.js"></script>
<script>
$(function(){
	$(".left-menu").fixcontent();
});
</script>