<?php
use fay\helpers\Html;
use fay\services\File;

echo F::form()->open();
?>
<div class="row">
	<div class="col-6">
		<div class="form-field">
			<label class="title bold">登录名</label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'form-control mw400',
				'disabled'=>'disabled',
				'readonly'=>'readonly',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">密码</label>
			<?php echo F::form()->inputText('password', array(
				'class'=>'form-control mw400',
			))?>
			<p class="description">若为空，则不会修改密码字段</p>
		</div>
		<div class="form-field">
			<label class="title bold">邮箱</label>
			<?php echo F::form()->inputText('email', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">手机号</label>
			<?php echo F::form()->inputText('mobile', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">姓名</label>
			<?php echo F::form()->inputText('realname', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">昵称</label>
			<?php echo F::form()->inputText('nickname', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">头像</label>
			<div id="avatar-container"><?php
				echo Html::inputHidden('avatar', $user['user']['avatar']['id'], array('id'=>'avatar-id'));
				echo Html::inputHidden('avatar', $user['user']['avatar']['id'], array('id'=>'avatar-id'));
				if(!empty($user['user']['avatar']['id'])){
					echo Html::link(Html::img($user['user']['avatar']['id'], File::PIC_RESIZE, array(
						'dw'=>178,
						'dh'=>178,
						'id'=>'avatar-img',
					)), $user['user']['avatar']['url'], array(
						'encode'=>false,
						'class'=>'fancybox-image',
						'title'=>false,
					));
					echo Html::link(Html::img($user['user']['avatar']['thumbnail'], File::PIC_THUMBNAIL, array(
						'id'=>'avatar-img-circle',
					)), $user['user']['avatar']['url'], array(
						'encode'=>false,
						'class'=>'fancybox-image',
						'title'=>false,
					));
				}else{
					echo Html::inputHidden('avatar', '0', array('id'=>'avatar-id'));
					echo Html::link(Html::img($this->assets('images/avatar.png'), 0, array(
						'id'=>'avatar-img',
					)), $this->assets('images/avatar.png'), array(
						'class'=>'fancybox-image',
						'encode'=>false,
						'title'=>false,
					));
					echo Html::link(Html::img($this->assets('images/avatar.png'), 0, array(
						'id'=>'avatar-img-circle',
					)), $this->assets('images/avatar.png'), array(
						'class'=>'fancybox-image',
						'encode'=>false,
						'title'=>false,
					));
				}
				echo Html::link('上传头像', 'javascript:;', array(
					'id'=>'upload-avatar',
					'class'=>'btn btn-grey',
				));
			?></div>
		</div>
	</div>
	<div class="col-6" id="prop-panel">
		<?php $this->renderPartial('prop/_edit')?>
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
user.user_id = <?php echo \F::form()->getData('id')?>;
user.init();
</script>