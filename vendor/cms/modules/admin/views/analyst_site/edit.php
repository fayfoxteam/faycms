<div class="row">
	<div class="col-5">
		<?php echo F::form()->open()?>
			<div class="form-field">
				<label>名称</label>
				<?php echo F::form()->inputText('title', array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="form-field">
				<label>描述</label>
				<?php echo F::form()->textarea('description', array(
					'class'=>'form-control autosize',
				))?>
			</div>
			<div class="form-field">
				<a href="javascript:;" class="btn" id="form-submit">编辑站点</a>
			</div>
		<?php echo F::form()->close()?>
	</div>
	<div class="col-7">
		<?php include '_right.php';?>
	</div>
</div>