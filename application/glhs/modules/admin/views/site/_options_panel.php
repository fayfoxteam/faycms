<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<form id="form" action="" method="post" class="validform">
	<div class="row">
		<div class="col-6">
			<div class="form-field">
				<label class="title">站点名称</label>
				<?php echo Html::inputText('site:sitename', Option::get('site:sitename'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">版权信息</label>
				<?php echo Html::inputText('site:copyright', Option::get('site:copyright'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">备案信息</label>
				<?php echo Html::inputText('site:beian', Option::get('site:beian'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电话</label>
				<?php echo Html::inputText('site:phone', Option::get('site:phone'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">官方微博</label>
				<?php echo Html::inputText('site:weibo', Option::get('site:weibo'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">电子邮箱</label>
				<?php echo Html::inputText('site:email', Option::get('site:email'), array(
					'class'=>'form-control',
					'data-rule'=>'email',
				))?>
			</div>
			<div class="form-field">
				<label class="title">QQ</label>
				<?php echo Html::inputText('site:qq', Option::get('site:qq'), array(
					'class'=>'form-control',
				))?>
			</div>
		</div>
		<div class="col-6">
			<div class="form-field">
				<label class="title">首页Title</label>
				<?php echo Html::inputText('site:seo_index_title', Option::get('site:seo_index_title'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Keywords</label>
				<?php echo Html::textarea('seo_index_keywords', Option::get('site:seo_index_keywords'), array(
					'class'=>'form-control h90 autosize',
				))?>
			</div>
			<div class="form-field">
				<label class="title">首页Description</label>
				<?php echo Html::textarea('seo_index_description', Option::get('site:seo_index_description'), array(
					'class'=>'form-control h90 autosize',
				))?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="form-field">
		<a href="javascript:;" class="btn" id="form-submit">提交保存</a>
	</div>
</form>