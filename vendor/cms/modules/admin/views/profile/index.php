<?php
use fay\helpers\Html;
use fay\models\File;

echo F::form()->open(null, array(), 'post', array('id'=>'form'));
?>
<div class="col-2-1">
	<div class="col-left">
		<div class="form-field">
			<label class="title">登录名</label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'w300',
				'disabled'=>'disabled',
				'readonly'=>'readonly'))?>
		</div>
		<div class="form-field">
			<label class="title">密码</label>
			<?php echo F::form()->inputText('password', array(
				'class'=>'w300',
			))?>
			<p class="description">若为空，则不会修改密码字段</p>
		</div>
		<div class="form-field">
			<label class="title">邮箱</label>
			<?php echo F::form()->inputText('email', array(
				'class'=>'w300',))?>
		</div>
		<div class="form-field">
			<label class="title">手机号</label>
			<?php echo F::form()->inputText('cellphone', array(
				'class'=>'w300'))?>
		</div>
		<div class="form-field">
			<label class="title">姓名</label>
			<?php echo F::form()->inputText('realname', array('class'=>'w300'))?>
		</div>
		<div class="form-field">
			<label class="title">昵称</label>
			<?php echo F::form()->inputText('nickname', array('class'=>'w300'))?>
		</div>
		<div class="form-field">
			<label class="title">头像</label>
			<div id="avatar-container">
				<?php 
				echo F::form()->inputHidden('avatar', array('id'=>'avatar-id'));
				if(!empty($user['avatar'])){
					echo Html::link(Html::img($user['avatar'], File::PIC_ZOOM, array(
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
					echo Html::link(Html::img($this->url().'images/avatar.png', 0, array(
						'id'=>'avatar-img',
					)), $this->url().'images/avatar.png', array(
						'class'=>'fancybox-image',
						'encode'=>false,
						'title'=>false,
					));
					echo Html::link(Html::img($this->url().'images/avatar.png', 0, array(
						'id'=>'avatar-img-circle',
					)), $this->url().'images/avatar.png', array(
						'class'=>'fancybox-image',
						'encode'=>false,
						'title'=>false,
					));
				}
				echo Html::link('上传头像', 'javascript:;', array(
					'id'=>'upload-avatar',
					'class'=>'btn-2',
				));
				?>
			</div>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="btn-1" id="form-submit">保存</a>
		</div>
	</div>
	<div class="col-right" id="prop-panel">
		<?php $this->renderPartial('prop/_edit', array(
			'props'=>$role['props'],
			'data'=>$user['props'],
		))?>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/browserplus-min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/user.js"></script>
<script>
user.user_id = <?php echo $user['id']?>;
user.init();
</script>