<?php use fay\helpers\Html;?>
<div class="box category-post" id="<?php echo Html::encode($alias)?>">
	<div class="box-title">
		<h3><?php
			echo Html::link('', array('cat/'.$data['top']), array(
				'class'=>'more-link',
			));
			echo Html::encode($data['title']);
		?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<div class="gallery-container">
					<ul>
					<?php foreach($posts as $p){?>
						<li><?php
							if(!empty($data['date_format'])){
								echo '<span class="time">'.date($data['date_format'], $p['publish_time']).'</span>';
							}
							echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])));
						?></li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div></div></div></div>
	</div>
</div>