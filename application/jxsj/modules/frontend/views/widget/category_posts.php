<?php
use fay\helpers\HtmlHelper;
?>
<div class="box category-post" id="<?php echo HtmlHelper::encode($widget->alias)?>">
	<div class="box-title">
		<h3><?php
			echo HtmlHelper::link('', array('cat/'.$widget->config['top']), array(
				'class'=>'more-link',
			));
			echo HtmlHelper::encode($widget->config['title']);
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
							echo HtmlHelper::link($p['post']['title'], $p['post']['link']);
						?></li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div></div></div></div>
	</div>
</div>