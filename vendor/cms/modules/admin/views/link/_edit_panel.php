<?php
use fay\helpers\Html;
?>
<div class="form-field">
	<label class="title">名称<em class="color-red">*</em></label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'w300',
	))?>
	<p class="description">例如：百度</p>
</div>
<div class="form-field">
	<label class="title">网址<em class="color-red">*</em></label>
	<?php echo F::form()->inputText('url', array(
		'class'=>'w300',
	))?>
	<p class="description">例子：http://www.fayfox.com/ —— 不要忘了 http://</p>
</div>
<div class="form-field">
	<label class="title">描述</label>
	<?php echo F::form()->textarea('description', array('class'=>'w550 h90 autosize'))?>
	<p class="description">通常，当访客将鼠标光标悬停在链接表链接的上方时，它会显示出来。根据主题的不同，也可能显示在链接下方。</p>
</div>
<div class="form-field">
	<label class="title">打开方式</label>
	<p>
		<?php echo F::form()->inputRadio('target', '_blank', array('label'=>'_blank — 新窗口或新标签。'), true)?>
	</p>
	<p>
		<?php echo F::form()->inputRadio('target', '_top', array('label'=>'_top — 不包含框架的当前窗口或标签。'))?>
	</p>
	<p>
		<?php echo F::form()->inputRadio('target', '_none', array('label'=>'_none — 同一窗口或标签。'))?>
	</p>
	<p class="description">为您的链接选择目标框架。</p>
</div>
<div class="form-field">
	<label class="title">可见性</label>
	<p>
		<?php echo F::form()->inputCheckbox('visiable', '0', array(
			'label'=>'将这个链接设置为不可见',
		))?>
	</p>
	<p class="description">前台是否可见</p>
</div>
<div class="form-field">
	<label class="title">排序</label>
	<?php echo F::form()->inputText('sort', array(
		'data-rule'=>'int',
		'data-params'=>'{max:255}',
	), 100)?>
	<p class="description"></p>
</div>
<div class="form-field">
	<label class="title">分类</label>
	<?php echo F::form()->select('cat_id', array(''=>'--分类--') + Html::getSelectOptions($cats, 'id', 'title'));?>
	<p class="description">分类效果视主题而定，可留空</p>
</div>
<div class="form-field">
	<label class="title">Logo</label>
	<div id="upload-logo-preview">
		<?php echo F::form()->inputHidden('logo', array(
			'data-rule'=>'int',
		));
		if(F::form()->getData('logo')){
			echo Html::img(F::form()->getData('logo'));
		}
		?>
	</div>
	<div id="upload-logo-container">
		<a href="javascript:;" id="upload-logo-link" class="btn-2">上传Logo</a>
	</div>
	<p class="description">是否需要Logo视主题而定</p>
</div>