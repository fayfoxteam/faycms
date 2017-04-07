<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
	<div class="col-5">
		<?php echo F::form()->open(array('cms/admin/tag/create'))?>
			<?php $this->renderPartial('_edit_panel');?>
			<div class="form-field">
				<a href="javascript:;" class="btn" id="form-submit">添加新标签</a>
			</div>
		<?php echo F::form()->close()?>
	</div>
	<div class="col-7">
		<?php $this->renderPartial('_right', array(
			'listview'=>$listview
		));?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
	$(".tag-sort").feditsort({
		'url':system.url("admin/tag/sort")
	});
});
</script>