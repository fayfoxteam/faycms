<?php
use fay\helpers\Html;
use fay\models\Category;

$cat = Category::model()->get($config['top'], 'title,alias');
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="box-2">
		<div class="box-2-title">
			<h3><?php echo Html::link($cat['title'], array('cat/'.$config['top']))?></h3>
		</div>
		<div class="box-2-content">
			<ul>
			<?php foreach($posts as $p){
				echo Html::link('<span>'.Html::encode($p['title']).'</span>', array('post/'.$p['id']), array(
					'title'=>Html::encode($p['title']),
					'encode'=>false,
					'wrapper'=>'li',
					'append'=>array(
						'tag'=>'time',
						'text'=>$p['format_publish_time'],
					),
				));
			}?>
			</ul>
		</div>
	</div>
</div>