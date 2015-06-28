<?php
?>
<div class="row">
	<div class="col-6">
		<form id="form" class="validform" action="<?php echo $this->url('admin/tag/edit', F::app()->input->get())?>" method="post">
			<?php $this->renderPartial('_edit_panel');?>
			<div class="form-field">
				<a href="javascript:;" class="btn" id="form-submit">编辑标签</a>
			</div>
		</form>
	</div>
	<div class="col-6">
		<?php $this->renderPartial('_right');?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$(".tag-sort").feditsort({
		'url':system.url("admin/tag/sort")
	});
});
</script>