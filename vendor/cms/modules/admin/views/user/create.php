<?php
use fay\helpers\Html;

echo F::form()->open();
?>
<div class="col-2-1">
	<div class="col-left">
		<div class="form-field">
			<label class="title">用户名<em class="color-red">*</em></label>
			<?php echo F::form()->inputText('username', array(
				'class'=>'w300',
			))?>
		</div>
		<div class="form-field">
			<label class="title">密码<em class="color-red">*</em></label>
			<?php echo F::form()->inputText('password', array(
				'class'=>'w300',
			))?>
		</div>
		<div class="form-field">
			<label class="title">手机号码</label>
			<?php echo F::form()->inputText('cellphone', array(
				'class'=>'w300',
			))?>
		</div>
		<div class="form-field">
			<label class="title">邮箱</label>
			<?php echo F::form()->inputText('email', array(
				'class'=>'w300',
			))?>
		</div>
		<div class="form-field">
			<label class="title">角色<em class="color-red">*</em></label>
			<?php echo F::form()->select('role', Html::getSelectOptions($roles, 'id', 'title'), array(
				'class'=>'w300',
			))?>
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
				echo Html::link('上传头像', 'javascript:;', array(
					'id'=>'upload-avatar',
					'class'=>'btn-2',
				));
			?>
			</div>
		</div>
		<div class="form-field">
			<?php echo F::form()->submitLink('添加用户', array(
				'class'=>'btn-1',
			))?>
		</div>
	</div>
	<div class="col-right" id="prop-panel">
		<?php $this->renderPartial('prop/_edit', array(
			'props'=>$role['props'],
		))?>
	</div>
</div>
<?php echo F::form()->close()?>

<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/browserplus-min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/user.js"></script>
<script>
user.init();
</script>