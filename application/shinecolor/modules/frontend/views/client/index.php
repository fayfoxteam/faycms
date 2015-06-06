<?php
use fay\helpers\Html;
?>
<div class="w1000 clearfix col-2">
	<div class="col-2-left">
		<nav class="left-menu">
			<ul>
				<li><a href="<?php echo $this->url('client')?>">合作客户</a></li>
			</ul>
		</nav>
	</div>
	<div class="col-2-right">
		<div class="page-item">
			<header>
				<span class="title">合作客户</span>
				<span class="dashed"></span>
			</header>
			<div class="content">
				<ul id="client-list">
				<?php foreach($links as $l){?>
					<li><?php echo Html::link(Html::img($l['logo'], 1, array(
						'width'=>142,
						'height'=>false,
					)), $l['url'], array(
						'target'=>'_blank',
						'encode'=>false,
						'target'=>$l['target'],
						'title'=>$l['description'],
					))?></li>
				<?php }?>
				</ul>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $this->url()?>js/custom/fayfox.fixcontent.js"></script>
<script>
$(function(){
	$(".left-menu").fixcontent();
});
</script>