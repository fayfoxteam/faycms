<?php
echo F::form()->open()?>
<div class="row">
	<div class="col-6">
		<div class="form-field">
			<label class="title bold">登录名<em class="required">*</em></label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'form-control mw400',
				'disabled'=>'disabled',
			))?>
		</div>
		<?php $this->renderPartial('_edit_panel')?>
	</div>
	<div class="col-6" id="prop-panel">
		<?php $this->renderPartial('prop/_edit', array(
			'props'=>$role['props'],
			'data'=>$user['props'],
		))?>
	</div>
</div>
<div class="form-field">
	<?php echo F::form()->submitLink('保存', array(
		'class'=>'btn',
	))?>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/browserplus-min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/user.js')?>"></script>
<script>
user.user_id = <?php echo $user['id']?>;
user.init();
</script>