<?php
use fay\helpers\Html;
use fay\models\Category;

$category = Category::model()->get($config['top']);
?>
<div class="box category-post" id="<?php echo Html::encode($alias)?>">
	<div class="box-title">
		<h3><?php
			echo Html::link('', array('cat/'.$config['top']), array(
				'class'=>'more-link',
			));
			echo Html::encode($category['title']);
		?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<div class="gallery-container">
					<ul>
					<?php foreach($posts as $p){?>
						<li><?php
							if(!empty($p['post']['format_publish_time'])){
								echo '<span class="time">'.$p['post']['format_publish_time'].'</span>';
							}
							echo Html::link($p['post']['title'], $p['post']['link']);
						?></li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div></div></div></div>
	</div>
</div>