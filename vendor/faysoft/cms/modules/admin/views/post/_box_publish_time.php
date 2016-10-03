<?php
use fay\helpers\Date;
use fay\helpers\Html;
?>
<div class="box" id="box-publish-time" data-name="publish_time">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>发布时间</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('publish_time', array('class'=>'form-control timepicker'))?>
		<p class="fc-grey mt5">留空默认为当前时间</p>
		<?php if(F::form()->getData('create_time') !== null){?>
		<p class="misc-pub-section mt6 pl0">
			<span>创建时间：</span>
			<?php echo Html::tag('abbr', array(
				'class'=>'time',
				'title'=>Date::format($post['create_time']),
			), Date::niceShort($post['create_time']))?>
		</p>
		<?php }?>
	</div>
</div>