<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<form id="form" action="" method="post" class="validform">
	<div class="col-2-1">
		<div class="col-left">
			<div class="form-field">
				<label class="title">站点名称</label>
				<?php echo Html::inputText('sitename', Option::get('sitename'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">版权信息</label>
				<?php echo Html::inputText('copyright', Option::get('copyright'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">备案信息</label>
				<?php echo Html::inputText('beian', Option::get('beian'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电话</label>
				<?php echo Html::inputText('phone', Option::get('phone'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">传真</label>
				<?php echo Html::inputText('fax', Option::get('fax'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电子邮箱</label>
				<?php echo Html::inputText('email', Option::get('email'), array(
					'class'=>'w400',
					'data-rule'=>'email',
				))?>
			</div>
			<div class="form-field">
				<label class="title">地址</label>
				<?php echo Html::inputText('address', Option::get('address'), array(
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
				<?php echo Html::inputText('seo_index_title', Option::get('seo_index_title'), array(
					'class'=>'w400',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Keywords</label>
				<?php echo Html::textarea('seo_index_keywords', Option::get('seo_index_keywords'), array(
					'class'=>'w400 h90 autosize',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Description</label>
				<?php echo Html::textarea('seo_index_description', Option::get('seo_index_description'), array(
					'class'=>'w400 h90 autosize',
				))?>
			</div>
		</div>
	</div>
</form>