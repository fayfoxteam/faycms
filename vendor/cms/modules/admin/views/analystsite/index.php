<?php
?>
<div class="col-2-3">
	<div class="col-right">
		<?php include '_right.php';?>
	</div>
	<div class="col-left">
		<form id="form" class="validform" action="<?php echo $this->url('admin/analyst-site/create')?>" method="post">
			<div class="form-field">
				<label>名称</label>
				<?php echo F::form()->inputText('title', array(
					'class'=>'full-width',
				))?>
			</div>
			<div class="form-field">
				<label>描述</label>
				<?php echo F::form()->textarea('description', array(
					'class'=>'full-width',
				))?>
			</div>
			<div class="form-field">
				<a href="javascript:;" class="btn-1" id="form-submit">添加站点</a>
			</div>
		</form>
	</div>
</div>