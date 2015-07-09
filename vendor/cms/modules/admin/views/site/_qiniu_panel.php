<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<div class="form-field">
	<label class="title">accessKey<em class="required">*</em></label>
	<?php echo Html::inputText('qiniu:accessKey', Option::get('qiniu:accessKey'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">从七牛开发者中心-帐号-密钥获取</p>
</div>
<div class="form-field">
	<label class="title">secretKey<em class="required">*</em></label>
	<?php echo Html::inputText('qiniu:secretKey', Option::get('qiniu:secretKey'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">从七牛开发者中心-帐号-密钥获取</p>
</div>
<div class="form-field">
	<label class="title">bucket<em class="required">*</em></label>
	<?php echo Html::inputText('qiniu:bucket', Option::get('qiniu:bucket'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">空间。在七牛管理平台创建</p>
</div>
<div class="form-field">
	<label class="title">domain<em class="required">*</em></label>
	<?php echo Html::inputText('qiniu:domain', Option::get('qiniu:domain'), array(
		'class'=>'form-control mw400',
		'data-required'=>'required',
	))?>
	<p class="description">七牛域名，在七牛管理平台设置。需要http://和末尾斜杠，例如：http://pic.faycms.com/</p>
</div>