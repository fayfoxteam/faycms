<?php
use fay\helpers\Html;

/**
 * @var string $alias
 * @var array $config
 * @var array $tags
 */
?>
<div class="widget widget-tag-cloud">
	<h5 class="widget-title"><?php echo Html::encode($config['title'])?></h5>
	<div class="cf tag-cloud"><?php
		foreach($tags as $t){
			echo Html::link($t['tag']['title'], $t['tag']['link'], array(
				'title'=>$t['counter']['posts'] . '篇文章'
			));
		}
	?></div>
</div>