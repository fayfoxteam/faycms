<?php
?>
<div class="col-2-3">
	<div class="col-right">
		<?php $this->renderPartial('_right');?>
	</div>
	<div class="col-left">
		<form id="form" class="validform" action="<?php echo $this->url('admin/tag/create')?>" method="post">
			<?php $this->renderPartial('_edit_panel');?>
			<div class="form-field">
				<a href="javascript:;" class="btn-1" id="form-submit">添加新标签</a>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$(".tag-sort").feditsort({
		'url':system.url("admin/tag/sort")
	});
});
</script>