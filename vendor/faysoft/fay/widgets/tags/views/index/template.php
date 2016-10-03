<?php
use fay\helpers\Html;

/**
 * @var string $alias
 * @var array $config
 * @var array $tags
 */
?>
<div class="widget widget-tags" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<div class="cf tag-cloud"><?php
			foreach($tags as $t){
				echo Html::link($t['tag']['title'], $t['tag']['link'], array(
					'title'=>$t['counter']['posts'] . '篇文章'
				));
			}
		?></div>
	</div>
</div>