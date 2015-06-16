<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($posts as $p){?>
			<li><?php
				echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $config['uri'])));
				if(!empty($config['date_format'])){
					echo '<span class="time">'.($config['date_format'] == 'pretty' ?
						Date::niceShort($p['publish_time']) : \date($config['date_format'], $p['publish_time'])).'</span>';
				}
			?></li>
		<?php }?>
		</ul>
	</div>
</div>