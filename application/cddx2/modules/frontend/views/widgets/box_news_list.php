<?php
use fay\helpers\Html;
use fay\models\Category;
use fay\helpers\String;

$cat = Category::model()->get($config['top'], 'title,alias');
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="box">
		<div class="box-title">
			<h3><?php echo Html::encode($cat['title'])?></h3>
			<em><?php echo str_replace('_', ' ', $cat['alias'])?></em>
			<?php echo Html::link('more..', array('cat/' . $config['top']), array(
				'class'=>'more-link',
			))?>
		</div>
		<div class="box-content">
			<div class="box-top-news">
				<?php $first_post = array_shift($posts);?>
				<h4><?php echo Html::link($first_post['title'], array('post/'.$first_post['id']))?></h4>
				<p>
					<?php echo Html::encode(String::niceShort($first_post['abstract'], 54))?>
					<?php echo Html::link('[详情]', array('post/'.$first_post['id']))?>
				</p>
			</div>
			<ul class="box-news-list">
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