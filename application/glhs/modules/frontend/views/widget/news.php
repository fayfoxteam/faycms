<?php
use fay\helpers\HtmlHelper;
?>
<div class="box left news">
	<h3 class="box-title">
		<span><?php echo HtmlHelper::encode($widget->config['title'])?></span>
	</h3>
	<div class="box-content">
		<ul><?php foreach($posts as $p){
			echo HtmlHelper::link($p['title'], array('news-'.$p['id']), array(
				'before'=>array(
					'tag'=>'time',
					'text'=>$p['format_publish_time'],
				),
				'wrapper'=>'li',
			));
		}?></ul>
		<?php echo HtmlHelper::link('+MORE', array('news'), array(
			'class'=>'more-link',
		))?>
	</div>
</div>