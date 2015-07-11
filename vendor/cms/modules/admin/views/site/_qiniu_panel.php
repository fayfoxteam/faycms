<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<form id="qiniu-form" class="site-settings-form" action="<?php echo $this->url('admin/site/set-options')?>">
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<label class="title">accessKey<em class="required">*</em></label>
				<?php echo Html::inputText('qiniu:accessKey', Option::get('qiniu:accessKey'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'accessKey',
				))?>
				<p class="description">从七牛开发者中心-帐号-密钥获取</p>
			</div>
			<div class="form-field">
				<label class="title">secretKey<em class="required">*</em></label>
				<?php echo Html::inputText('qiniu:secretKey', Option::get('qiniu:secretKey'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'secretKey',
				))?>
				<p class="description">从七牛开发者中心-帐号-密钥获取</p>
			</div>
			<div class="form-field">
				<label class="title">bucket<em class="required">*</em></label>
				<?php echo Html::inputText('qiniu:bucket', Option::get('qiniu:bucket'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'bucket',
				))?>
				<p class="description">空间。在七牛管理平台创建</p>
			</div>
			<div class="form-field">
				<label class="title">domain</label>
				<?php echo Html::inputText('qiniu:domain', Option::get('qiniu:domain'), array(
					'class'=>'form-control mw400',
				))?>
				<p class="description">若绑定了独立域名（在七牛管理平台设置）可指定域名。需要http://和末尾斜杠，例如：<code>http://pic.faycms.com/</code></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<a href="javascript:;" id="qiniu-form-submit" class="btn">提交保存</a>
			</div>
		</div>
	</div>
</form>