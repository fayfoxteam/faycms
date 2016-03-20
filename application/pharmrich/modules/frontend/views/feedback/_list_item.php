<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<li class="contact-item" id="contact-<?php echo $data['id']?>">
	<h3><?php echo Html::encode($data['title'])?></h3>
	<div class="ci-header"><?php
		echo Html::tag('span', array(
			'class'=>'ci-name',
			'title'=>'Name',
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-user',
				'text'=>'',
			),
		), Html::encode($data['name']));
		echo Html::tag('span', array(
			'class'=>'ci-time',
			'title'=>Date::format($data['publish_time']),
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-calendar',
				'text'=>'',
			),
		), date('d M Y', $data['publish_time']));
		if($data['country']){
			echo Html::tag('span', array(
				'class'=>'ci-country',
				'title'=>'Country',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-location-arrow',
					'text'=>'',
				),
			), Html::encode($data['country']));
		}
		echo Html::tag('span', array(
			'class'=>'ci-time',
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-map-marker',
				'text'=>'',
			),
		), preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/', '$1.$2.*.*', long2ip($data['show_ip_int'])));
	?></div>
	<div class="ci-content"><?php echo nl2br(Html::encode($data['content']))?></div>
	<?php if($data['reply']){?>
		<div class="ci-reply"><?php if($data['reply']){
			echo Html::tag('strong', array(), 'Reply：'), Html::tag('span', array(
				'class'=>'ci-reply-container',
			), nl2br(Html::encode($data['reply'])));
		}?></div>
	<?php }?>
</li>