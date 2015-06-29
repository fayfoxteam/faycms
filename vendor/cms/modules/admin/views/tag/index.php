<?php
?>
<div class="row">
	<div class="col-5">
		<form id="form" class="validform" action="<?php echo $this->url('admin/tag/create')?>" method="post">
			<?php $this->renderPartial('_edit_panel');?>
			<div class="form-field">
				<a href="javascript:;" class="btn" id="form-submit">添加新标签</a>
			</div>
		</form>
	</div>
    <div class="col-7">
        <?php $this->renderPartial('_right');?>
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