<?php
use fay\helpers\Html;
use fay\services\OptionService;
?>
<form id="options-form" class="site-settings-form" action="<?php echo $this->url('admin/site/set-options')?>">
	<div class="row">
		<div class="col-6">
			<div class="form-field">
				<label class="title">站点名称</label>
				<?php echo Html::inputText('site:sitename', OptionService::get('site:sitename'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">版权信息</label>
				<?php echo Html::inputText('site:copyright', OptionService::get('site:copyright'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">版权信息（英文）</label>
				<?php echo Html::inputText('site:copyright_en', OptionService::get('site:copyright_en'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">邮编</label>
				<?php echo Html::inputText('site:postcode', OptionService::get('site:postcode'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">主办</label>
				<?php echo Html::inputText('site:oganizer', OptionService::get('site:oganizer'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电子邮箱</label>
				<?php echo Html::inputText('site:email', OptionService::get('site:email'), array(
					'class'=>'form-control',
					'data-rule'=>'email',
				))?>
			</div>
			<div class="form-field">
				<label class="title">地址</label>
				<?php echo Html::inputText('site:address', OptionService::get('site:address'), array(
					'class'=>'form-control',
				))?>
			</div>
		</div>
		<div class="col-6">
			<div class="form-field">
				<label class="title">首页Title</label>
				<?php echo Html::inputText('site:seo_index_title', OptionService::get('site:seo_index_title'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Keywords</label>
				<?php echo Html::textarea('site:seo_index_keywords', OptionService::get('site:seo_index_keywords'), array(
					'class'=>'form-control h90 autosize',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Description</label>
				<?php echo Html::textarea('site:seo_index_description', OptionService::get('site:seo_index_description'), array(
					'class'=>'form-control h90 autosize',
				))?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<a href="javascript:;" id="options-form-submit" class="btn">提交保存</a>
			</div>
		</div>
	</div>
</form>