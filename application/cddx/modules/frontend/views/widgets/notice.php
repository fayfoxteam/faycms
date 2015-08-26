<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<div class="box" id="widget-<?php echo Html::encode($alias)?>">
	<header class="box-title">
		<?php echo Html::link('More..', array('cat-'.$data['top']), array(
			'class'=>'more',
		))?>
		<h3><span><?php echo Html::encode($data['title'])?></span><em></em></h3>
	</header>
	<div class="box-content">
		<marquee direction="up" onMouseOut="this.start()" onMouseOver="this.stop()" scrollamount="2">
			<ul>
			<?php foreach($posts as $p){?>
				<li><?php
					echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])));
					if(Date::isThisMonth($p['publish_time'])){
						echo '<span class="fc-red">[new]</span>';
					}
					if(!empty($data['date_format'])){
						echo '<time class="time">', date($data['date_format'], $p['publish_time']), '</time>';
					}
				?></li>
			<?php }?>
			</ul>
		</marquee>
	</div>
</div>