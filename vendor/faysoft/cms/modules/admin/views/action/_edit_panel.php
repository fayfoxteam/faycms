<?php
use fay\helpers\Html;
?>
<div class="form-field">
	<label class="title bold">操作<em class="required">*</em></label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title bold">路由<em class="required">*</em></label>
	<?php echo F::form()->inputText('router', array(
		'class'=>'form-control',
	))?>
	<p class="description">
		路由作为唯一识别标志，但并不一定是真实路由
	</p>
</div>
<div class="form-field">
	<label class="title bold">分类<em class="required">*</em></label>
	<?php echo F::form()->select('cat_id', Html::getSelectOptions($cats, 'id', 'title'), array(
		'class'=>'form-control',
	));?>
</div>
<div class="form-field">
	<label class="title bold">父级</label>
	<?php echo F::form()->inputText('parent_router', array(
		'class'=>'form-control',
		'id'=>'parent-router',
	));?>
	<p class="description">
		当该节点被选中时，其父节点也会被强制选中<br />
		父子关系可继承，但不能多继承
	</p>
</div>
<div class="form-field">
	<?php echo F::form()->inputCheckbox('is_public', '1', array(
		'label'=>'公共',
	))?>
</div>