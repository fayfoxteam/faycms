<?php
use fay\helpers\Html;
use fay\services\OptionService;
?>
<form id="form" action="" method="post" class="validform">
	<div class="col-2-1">
		<div class="col-left">
			<div class="form-field">
				<label class="title">站点名称</label>
				<?php echo Html::inputText('sitename', OptionService::get('site.sitename'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">版权信息</label>
				<?php echo Html::inputText('copyright', OptionService::get('site.copyright'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">备案信息</label>
				<?php echo Html::inputText('beian', OptionService::get('site.beian'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">邮编</label>
				<?php echo Html::inputText('postcode', OptionService::get('site.postcode'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">主办</label>
				<?php echo Html::inputText('organizers', OptionService::get('site.organizers'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电子邮箱</label>
				<?php echo Html::inputText('email', OptionService::get('site.email'), array(
					'class'=>'w400',
					'data-rule'=>'email',
				))?>
			</div>
			<div class="form-field">
				<label class="title">地址</label>
				<?php echo Html::inputText('address', OptionService::get('site.address'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<a href="javascript:;" class="btn-1" id="form-submit">提交保存</a>
			</div>
		</div>
		<div class="col-right">
			<div class="form-field">
				<label class="title">首页Title</label>
				<?php echo Html::inputText('seo_index_title', OptionService::get('site.seo_index_title'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Keywords</label>
				<?php echo Html::textarea('seo_index_keywords', OptionService::get('site.seo_index_keywords'), array(
					'class'=>'w400 h90 autosize',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Description</label>
				<?php echo Html::textarea('seo_index_description', OptionService::get('site.seo_index_description'), array(
					'class'=>'w400 h90 autosize',
				))?>
			</div>
		</div>
	</div>
</form>