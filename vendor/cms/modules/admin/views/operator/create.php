<?php echo F::form()->open()?>
<div class="row">
	<div class="col-6">
		<div class="form-field">
			<label class="title">用户名<em class="required">*</em></label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<?php $this->renderPartial('_edit_panel')?>
	</div>
	<div class="col-6" id="prop-panel">
		<?php $this->renderPartial('prop/_edit', array(
			'props'=>$role['props'],
			'data'=>array(),
		))?>
	</div>
	<div class="clear"></div>
</div>
<div class="form-field">
	<?php echo F::form()->submitLink('添加', array(
		'class'=>'btn',
	))?>
</div>
<?php echo F::form()->close()?>

<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/user.js')?>"></script>
<script>
user.init();
</script>