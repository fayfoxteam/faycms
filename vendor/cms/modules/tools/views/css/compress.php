<?php
use fay\helpers\Html;
?>
<div class="row">
	<?php echo F::form()->open()?>
	<div class="col-6">
		<div class="box">
			<div class="box-title"><h3>before</h3></div>
			<div class="box-content"><?php echo F::form()->textarea('data', array(
				'class'=>'form-control h350',
			));?></div>
		</div>
		<?php echo F::form()->submitLink('Compress', array(
			'class'=>'btn',
		))?>
	</div>
	<div class="col-6">
		<div class="box">
			<div class="box-title"><h3>before</h3></div>
			<div class="box-content"><?php echo Html::textarea('after', $after_compress, array(
				'class'=>'form-control h350',
			))?></div>
		</div>
	</div>
	<?php echo F::form()->close()?>
</div>