<div class="col-2-3">
	<div class="col-right">
		<?php $this->renderPartial('_right')?>
	</div>
	<div class="col-left">
		<form id="form" action="<?php echo $this->url('admin/post-prop/create')?>" method="post" class="validform">
			<?php $this->renderPartial('_edit_panel')?>
			<div class="form-field">
				<a href="javascript:;" class="btn-1" id="form-submit">添加</a>
			</div>
		</form>
	</div>
</div>