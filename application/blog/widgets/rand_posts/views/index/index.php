<?php
use fay\helpers\Html;
?>
<aside class="widget recent-post">
	<div class="widget-title">相关文章</div>
	<ul>
	<?php 
		foreach($posts as $p){?>
		<li>
			<a href="<?php echo $this->url('post/'.$p['id'])?>">
				<?php echo Html::encode($p['title'])?>
			</a>
		</li>
	<?php }?>
	</ul>
</aside>