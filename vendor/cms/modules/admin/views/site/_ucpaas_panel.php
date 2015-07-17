<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<form id="ucpaas-form" class="site-settings-form" action="<?php echo $this->url('admin/site/set-options')?>">
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<label class="title">Account Sid<em class="required">*</em></label>
				<?php echo Html::inputText('ucpaas:accountsid', Option::get('ucpaas:accountsid'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'Account Sid',
				))?>
				<p class="description">从云之讯开放平台获取</p>
			</div>
			<div class="form-field">
				<label class="title">Auth Token<em class="required">*</em></label>
				<?php echo Html::inputText('ucpaas:token', Option::get('ucpaas:token'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'Auth Token',
				))?>
				<p class="description">从云之讯开放平台获取</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<a href="javascript:;" id="ucpaas-form-submit" class="btn">提交保存</a>
			</div>
		</div>
	</div>
</form>