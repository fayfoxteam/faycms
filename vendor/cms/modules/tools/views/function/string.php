<?php
use fay\helpers\String;
?>
<?php echo F::form()->open(null, 'post')?>
	<div class="row">
		<div class="col-12">
			<div class="box">
				<div class="box-title"><h3>Random</h3></div>
				<div class="box-content">
					<fieldset class="form-field">
					<?php
						echo F::form()->select('random_type', array(
							'alnum'=>'alnum',
							'basic'=>'basic',
							'numeric'=>'numeric',
							'nozero'=>'nozero',
							'alpha'=>'alpha',
							'unique'=>'unique',
						), array(
							'class'=>'form-control ib w150',
						));
						echo F::form()->inputNumber('random_length', array(
							'class'=>'form-control ib w50',
							'min'=>1,
						), 16);
						echo F::form()->submitLink('Submit', array(
							'class'=>'btn btn-sm',
						));
					?>
					</fieldset>
					<fieldset class="form-field"><?php echo String::random(F::form()->getData('random_type', 'alnum'), F::form()->getData('random_length', 16))?></fieldset>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="box">
				<div class="box-title"><h3>Length</h3></div>
				<div class="box-content">
					<fieldset class="form-field">
						<?php echo F::form()->textarea('length_string', array(
							'class'=>'form-control mw600 autosize',
							'id'=>'length_string',
						))?>
					</fieldset>
					<fieldset class="form-field">Length: <span id="string-length"><?php echo mb_strlen(F::form()->getData('length_string'), 'utf-8')?></span></fieldset>
				</div>
			</div>
		</div>
	</div>
<?php echo F::form()->close()?>
<script>
$(function(){
	$('#length_string').on('keyup', function(){
		$('#string-length').text($(this).val().length);
	});
});
</script>