<div class="row">
	<div class="col-5">
		<?php echo F::form()->open(array('admin/action/create'))?>
			<?php $this->renderPartial('_edit_panel');?>
			<div class="form-field">
				<?php echo F::form()->submitLink('添加权限', array(
					'class'=>'btn',
				))?>
			</div>
		<?php echo F::form()->close()?>
	</div>
	<div class="col-7">
		<?php include '_right.php';?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.autocomplete.js"></script>
<script>
$(function(){
	$("#parent-router").autocomplete({
		"url" : system.url('admin/action/search'),
		'startSuggestLength':0,
		'onSelect':function(obj){
			common.validformParams.forms.default.obj.check(obj);
		}
	});
});
</script>