<?php
use fay\helpers\Html;
?>
<div class="w1000 clearfix col-2">
	<div class="col-2-left">
		<nav class="left-menu">
			<ul>
			<?php foreach($pages as $p){?>
				<li><a href="#page-<?php echo $p['alias']?>"><?php echo Html::encode($p['title'])?></a></li>
			<?php }?>
			</ul>
		</nav>
	</div>
	<div class="col-2-right">
		<div id="about-banner"><img src="<?php echo $this->staticFile('images/about.png')?>" /></div>
		<?php foreach($pages as $p){?>
			<div class="page-item" id="page-<?php echo $p['alias']?>">
				<header>
					<span class="title"><?php echo Html::encode($p['title'])?></span>
					<span class="dashed"></span>
				</header>
				<div class="content"><?php echo $p['content']?></div>
			</div>
		<?php }?>
	</div>
</div>
<script src="<?php echo $this->url()?>faycms/js/fayfox.fixcontent.js"></script>
<script src="<?php echo $this->url()?>js/jquery.scrollTo-min.js"></script>
<script>
$(function(){
	$(".left-menu").fixcontent();
	$(".left-menu a").click(function(){
		$.scrollTo($(this).attr("href"), 500);
		return false;
	});
});
</script>