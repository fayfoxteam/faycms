<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\tables\Users;
?>
<div class="form-field">
	<label class="title bold">登录名<em class="required">*</em></label>
	<?php echo F::form()->inputText('username', array(
		'class'=>'form-control mw400',
		'disabled'=>F::form()->getScene() == 'edit' ? 'disabled' : false,
		'readonly'=>F::form()->getScene() == 'edit' ? 'readonly' : false,
	))?>
</div>
<div class="form-field">
	<label class="title bold">密码</label>
	<?php
		echo F::form()->inputText('password', array(
			'class'=>'form-control mw400',
		));
		if(F::form()->getScene() == 'edit'){
			echo Html::tag('p', array(
				'class'=>'description',
			), '若为空，则不会修改密码字段');
		}
	?>
</div>
<div class="form-field">
	<label class="title bold">手机号码</label>
	<?php echo F::form()->inputText('mobile', array(
			'class'=>'form-control mw400',
		))?>
</div>
<div class="form-field">
	<label class="title bold">邮箱</label>
	<?php echo F::form()->inputText('email', array(
		'class'=>'form-control mw400',
	))?>
</div>
<?php if($roles){?>
<div class="form-field">
	<label class="title bold">角色</label>
	<?php foreach($roles as $r){
		echo F::form()->inputCheckbox('roles[]', $r['id'], array(
			'label'=>$r['title'],
			'class'=>'user-roles',
		));
	}?>
</div>
<?php }?>
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
	), Users::STATUS_VERIFIED)?>
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
		), true);
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
			)), File::getUrl($user['avatar']), array(
				'encode'=>false,
				'class'=>'fancybox-image',
				'title'=>false,
			));
			echo Html::link(Html::img($user['avatar'], File::PIC_THUMBNAIL, array(
				'id'=>'avatar-img-circle',
			)), File::getUrl($user['avatar']), array(
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