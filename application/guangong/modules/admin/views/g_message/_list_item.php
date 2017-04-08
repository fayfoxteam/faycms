<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;

/**
 * @var $data array
 * @var $iplocation IpLocation
 */
?>
<li class="contact-item" id="contact-<?php echo $data['id']?>">
	<div class="ci-options"><?php
		if(F::app()->checkPermission('admin/g-message/reply')){
			echo HtmlHelper::link('<i class="fa fa-reply-all"></i>', '#contact-reply-dialog', array(
				'data-id'=>$data['id'],
				'class'=>'btn btn-grey reply-link',
				'encode'=>false,
				'title'=>'回复',
			));
		}
		echo HtmlHelper::link('<i class="fa fa-trash"></i>', array('admin/g-message/remove', array(
			'id'=>$data['id'],
		)), array(
			'data-id'=>$data['id'],
			'class'=>'btn btn-grey remove-link',
			'encode'=>false,
			'title'=>'删除',
		), true);
	?></div>
	<div class="ci-header"><?php
		echo HtmlHelper::tag('span', array(
			'class'=>'ci-name',
			'title'=>'称呼',
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-user',
				'text'=>'',
			),
		), $data['nickname'] ? HtmlHelper::encode($data['nickname']) : '匿名');
		echo HtmlHelper::tag('span', array(
			'class'=>'ci-time',
			'title'=>DateHelper::format($data['create_time']),
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-calendar',
				'text'=>'',
			),
		), DateHelper::niceShort($data['create_time']));
		echo HtmlHelper::tag('span', array(
			'class'=>'ci-mobile',
			'title'=>'电话',
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-mobile-phone',
				'text'=>'',
			),
		), $data['mobile'] ? HtmlHelper::encode($data['mobile']) : '未填写');
		echo HtmlHelper::tag('span', array(
			'class'=>'ci-area',
			'prepend'=>array(
				'tag'=>'i',
				'class'=>'fa fa-map-marker',
				'text'=>'',
			),
			'title'=>long2ip($data['ip_int']),
		), $iplocation->getCountry(long2ip($data['ip_int'])));
		echo '(', long2ip($data['ip_int']), ')';
	?></div>
	<div class="ci-content"><?php echo nl2br(HtmlHelper::encode($data['content']))?></div>
	<div class="ci-reply"><?php if($data['reply']){
		echo HtmlHelper::tag('strong', array(), '管理员回复：'), HtmlHelper::tag('span', array(
			'class'=>'ci-reply-container',
		), nl2br(HtmlHelper::encode($data['reply'])));
	}else{
		echo '未回复';
	}?></div>
</li>