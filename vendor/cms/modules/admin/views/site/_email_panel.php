<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<div class="form-field">
	<label class="title">主机名<em class="required">*</em></label>
	<?php echo Html::inputText('email:Host', Option::get('email:Host'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">例如：smtp.163.com</p>
</div>
<div class="form-field">
	<label class="title">用户名<em class="required">*</em></label>
	<?php echo Html::inputText('email:Username', Option::get('email:Username'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">例如：admin@faycms.com</p>
</div>
<div class="form-field">
	<label class="title">密码<em class="required">*</em></label>
	<?php echo Html::inputText('email:Password', Option::get('email:Password'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
</div>
<div class="form-field">
	<label class="title">加密方式</label>
	<?php echo Html::inputText('email:SMTPSecure', Option::get('email:SMTPSecure'), array(
		'class'=>'form-control mw400',
	))?>
	<p class="description">可选<code>tls</code>, <code>ssl</code>。若无需加密，则留空</p>
</div>
<div class="form-field">
	<label class="title">端口号<em class="required">*</em></label>
	<?php echo Html::inputText('email:Port', Option::get('email:Port'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	), '25')?>
	<p class="description">一般是<span class="fc-orange">25</span></p>
</div>
<div class="form-field">
	<label class="title">显示名</label>
	<?php echo Html::inputText('email:FromName', Option::get('email:FromName'), array(
		'class'=>'form-control mw400',
	))?>
	<p class="description">你发出的所有邮件，发件人将显示此昵称</p>
</div>