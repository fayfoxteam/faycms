<?php
use fay\helpers\Html;

$crt_cat = F::input()->get('cat');
?>
<ul>
	<li><?php echo Html::link('全部', array(
		'product'
	), array(
		'class'=>($crt_cat == '') ? 'crt' : false,
	))?></li>
	<?php foreach($cats as $c){?>
		<li><?php echo Html::link($c['title'], array(
			'product/'.$c['alias']
		), array(
			'class'=>($crt_cat == $c['alias']) ? 'crt' : false,
		))?></li>
	<?php }?>
</ul>