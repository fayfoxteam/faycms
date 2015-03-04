<?php
use fay\helpers\Html;
?>
<div class="col-2-3">
	<div class="col-right">
		<?php include '_right.php';?>
	</div>
	<div class="col-left">
		<?php echo F::form()->open()?>
			<div class="form-field">
				<label class="title">操作<em class="color-red">*</em></label>
				<?php echo F::form()->inputText('title', array(
					'class'=>'full-width',
				))?>
			</div>
			<div class="form-field">
				<label class="title">路由<em class="color-red">*</em></label>
				<?php echo F::form()->inputText('router', array(
					'class'=>'full-width',
				))?>
				<p class="description">
					路由作为唯一识别标志，但并不一定是真实路由
				</p>
			</div>
			<div class="form-field">
				<label class="title">分类<em class="color-red">*</em></label>
				<?php echo F::form()->select('cat_id', Html::getSelectOptions($cats, 'id', 'title'));?>
			</div>
			<div class="form-field">
				<label class="title">父级</label>
				<?php echo F::form()->inputText('parent_router', array(
					'class'=>'full-width',
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
			<div class="form-field">
				<?php echo F::form()->submitLink('修改权限', array(
					'class'=>'btn-1',
				))?>
			</div>
		<?php echo F::form()->close()?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.autocomplete.js"></script>
<script>
$(function(){
	$("#parent-router").autocomplete({
		"url" : system.url('admin/action/search'),
		'startSuggestLength':7,
		'onSelect':function(obj){
			common.validformParams.forms.default.obj.check(obj);
		}
	});
});
</script>