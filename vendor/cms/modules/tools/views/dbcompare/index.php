<?php
use fay\helpers\Html;
?>
<form method="post" action="" id="form" class="validform">
	<div class="col-2-1">
		<div class="col-left">
			<div class="form-field">
				<?php echo Html::inputRadio('left[from]', 'local', true, array(
					'label'=>'本地数据库',
					'class'=>'left-from',
				))?>
				<?php echo Html::inputRadio('left[from]', 'other', false, array(
					'label'=>'第三方数据库',
					'class'=>'left-from',
				))?>
			</div>
			<div id="left-db" class="hide">
				<div class="form-field">
					<label class="title">Host<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('left[host]', array(
						'data-required'=>"Host can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">User<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('left[user]', array(
						'data-required'=>"User can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Password</label>
					<?php echo F::form()->inputText('left[password]', array(
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Db Name<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('left[dbname]', array(
						'data-required'=>"Db Name can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Table Prefix</label>
					<?php echo F::form()->inputText('left[prefix]', array(
						'class'=>'w300',
					))?>
				</div>
			</div>
		</div>
		<div class="col-right">
			<div class="form-field">
				<?php echo Html::inputRadio('right[from]', 'local', false, array(
					'label'=>'本地数据库',
					'class'=>'right-from',
				))?>
				<?php echo Html::inputRadio('right[from]', 'other', true, array(
					'label'=>'第三方数据库',
					'class'=>'right-from',
				))?>
			</div>
			<div id="right-db">
				<div class="form-field">
					<label class="title">Host<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('right[host]', array(
						'data-required'=>"Host can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">User<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('right[user]', array(
						'data-required'=>"User can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Password</label>
					<?php echo F::form()->inputText('right[password]', array(
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Db Name<em class="color-red">*</em></label>
					<?php echo F::form()->inputText('right[dbname]', array(
						'data-required'=>"Db Name can't be empty",
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">Table Prefix</label>
					<?php echo F::form()->inputText('right[prefix]', array(
						'class'=>'w300',
					))?>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="form-field">
	<a href="javascript:;" class="btn-1" id="form-submit">Submit</a>
</div>
<script>
$(function(){
	$('.left-from').on('change', function(){
		if($(this).val() == 'local'){
			$('#left-db').find('input').each(function(){
				$(this).poshytip('hide');
			});
			$('#left-db').hide();
		}else{
			$('#left-db').show();
		}
	});
	$('.right-from').on('change', function(){
		if($(this).val() == 'local'){
			$('#right-db').find('hidden').each(function(){
				$(this).poshytip('hide');
			});
			$('#right-db').hide();
		}else{
			$('#right-db').show();
		}
	});
});
</script>