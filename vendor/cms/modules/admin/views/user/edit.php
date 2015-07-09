<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\tables\Users;

echo F::form()->open()?>
<div class="row">
	<div class="col-6">
		<div class="form-field">
			<label class="title bold">用户名<em class="required">*</em></label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'form-control mw400',
				'disabled'=>'disabled',
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
			<label class="title bold">手机号码</label>
			<?php echo F::form()->inputText('cellphone', array(
					'class'=>'form-control mw400',
				))?>
		</div>
		<div class="form-field">
			<label class="title bold">邮箱</label>
			<?php echo F::form()->inputText('email', array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">角色<em class="required">*</em></label>
			<?php echo F::form()->select('role', Html::getSelectOptions($roles, 'id', 'title'), array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">姓名</label>
			<?php echo F::form()->inputText('realname', array('class'=>'form-control mw400'))?>
		</div>
		<div class="form-field">
			<label class="title bold">昵称</label>
			<?php echo F::form()->inputText('nickname', array('class'=>'form-control mw400'))?>
		</div>
		<div class="form-field">
			<label class="title bold">审核状态</label>
			<?php echo F::form()->select('status', array(
				Users::STATUS_UNCOMPLETED=>'用户信息不完整',
				Users::STATUS_PENDING=>'未审核',
				Users::STATUS_VERIFIED=>'通过审核',
				Users::STATUS_VERIFY_FAILED=>'未通过审核',
			), array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">登陆状态</label>
			<?php
				echo F::form()->inputRadio('block', 0, array(
					'wrapper'=>array(
						'tag'=>'label',
						'class'=>'fc-green',
					),
					'after'=>'正常登录',
				));
				echo F::form()->inputRadio('block', 1, array(
					'wrapper'=>array(
						'tag'=>'label',
						'class'=>'fc-red',
					),
					'after'=>'限制登录',
				));
			?>
		</div>
		<div class="form-field">
			<label class="title bold">头像</label>
			<div id="avatar-container">
				<?php 
				echo F::form()->inputHidden('avatar', array('id'=>'avatar-id'));
				if(!empty($user['avatar'])){
					echo Html::link(Html::img($user['avatar'], File::PIC_RESIZE, array(
						'dw'=>178,
						'dh'=>178,
						'id'=>'avatar-img',
					)), File::model()->getUrl($user['avatar']), array(
						'encode'=>false,
						'class'=>'fancybox-image',
						'title'=>false,
					));
					echo Html::link(Html::img($user['avatar'], File::PIC_THUMBNAIL, array(
						'id'=>'avatar-img-circle',
					)), File::model()->getUrl($user['avatar']), array(
						'encode'=>false,
						'class'=>'fancybox-image',
						'title'=>false,
					));
				}else{
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
				?>
			</div>
		</div>
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