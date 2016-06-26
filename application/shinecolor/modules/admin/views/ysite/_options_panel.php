<?php
use fay\helpers\Html;
use fay\services\Option;
?>
<form id="form" action="" method="post" class="validform">
	<div class="form-field">
		<label class="title">站点名称</label>
		<?php echo Html::inputText('site:sitename', Option::get('site:sitename'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">版权信息</label>
		<?php echo Html::inputText('site:shine_color_copyright', Option::get('site:shine_color_copyright'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">备案号</label>
		<?php echo Html::inputText('site:shine_color_beian', Option::get('site:shine_color_beian'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">首页关键词</label>
		<?php echo Html::inputText('site:seo_index_keywords', Option::get('site:seo_index_keywords'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">首页描述</label>
		<?php echo Html::textarea('seo_index_description', Option::get('site:seo_index_description'), array(
			'class'=>'w300 h90',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<a href="javascript:;" class="btn-1" id="form-submit">提交保存</a>
	</div>
</form>