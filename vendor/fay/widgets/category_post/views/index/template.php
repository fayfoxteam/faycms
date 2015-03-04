<?php
use fay\helpers\Html;
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($data['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($posts as $p){?>
			<li><?php
				echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])));
				if(!empty($data['date_format'])){
					echo '<span class="time">'.date($data['date_format'], $p['publish_time']).'</span>';
				}
			?></li>
		<?php }?>
		</ul>
	</div>
</div>