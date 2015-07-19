<?php
use fay\helpers\Html;
?>
<article class="<?php echo $alias?>">
	<div class="inner">
		<figure>
			<?php echo Html::img($page['thumbnail'])?>
		</figure>
		<h3><?php echo $page['title']?></h3>
		<div class="item-introtext">
			<?php echo $page['abstract']?>
		</div>
		<?php echo Html::link('查看详细', array(
			'page/'.$page['id'],
		), array(
			'class'=>'more',
		))?>
	</div>
</article>