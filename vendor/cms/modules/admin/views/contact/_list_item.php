<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<li class="contact-item" id="contact-<?php echo $data['id']?>">
	<div class="ci-options">
		<?php echo Html::link('<i class="fa fa-reply-all"></i>', '#contact-reply-dialog', array(
			'data-id'=>$data['id'],
			'class'=>'btn btn-grey reply-link',
			'encode'=>false,
			'title'=>'回复',
		))?>
		<?php echo Html::link('<i class="fa fa-trash"></i>', array('admin/contact/remove', array(
			'id'=>$data['id'],
		)), array(
			'data-id'=>$data['id'],
			'class'=>'btn btn-grey remove-link',
			'encode'=>false,
			'title'=>'删除',
		))?>
	</div>
	<h3><?php echo Html::encode($data['title'])?></h3>
	<div class="ci-header"><?php
		if(in_array('name', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-name',
				'title'=>'称呼',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-user',
					'text'=>'',
				),
			), Html::encode($data['name']));
		}
		if(in_array('create_time', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-time',
				'title'=>Date::format($data['create_time']),
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-calendar',
					'text'=>'',
				),
			), (empty($settings['display_time']) || $settings['display_time'] == 'short') ? Date::niceShort($data['create_time']) : Date::format($data['create_time']));
		}
		if(in_array('country', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-country',
				'title'=>'国家',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-location-arrow',
					'text'=>'',
				),
			), Html::encode($data['country']));
		}
		if(in_array('phone', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-phone',
				'title'=>'电话',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-mobile-phone',
					'text'=>'',
				),
			), Html::encode($data['phone']));
		}
		if(in_array('email', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-email',
				'title'=>'邮箱',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-envelope-o',
					'text'=>'',
				),
			), Html::encode($data['email']));
		}
		if(in_array('area', $settings['cols'])){
			echo Html::tag('span', array(
				'class'=>'ci-email',
				'title'=>'来源地区',
				'prepend'=>array(
					'tag'=>'i',
					'class'=>'fa fa-map-marker',
					'text'=>'',
				),
				'title'=>long2ip($data['ip_int']),
			), $iplocation->getCountry(long2ip($data['ip_int'])));
		}
		if(in_array('ip', $settings['cols'])){
			echo '(', long2ip($data['ip_int']), ')';
		}
	?></div>
	<div class="ci-content"><?php echo nl2br(Html::encode($data['content']))?></div>
	<div class="ci-reply"><?php if($data['reply']){
		echo Html::tag('strong', array(), '管理员回复：'), Html::tag('span', array(
			'class'=>'ci-reply-container',
		), nl2br(Html::encode($data['reply'])));
	}else{
		echo '未回复';
	}?></div>
</li>