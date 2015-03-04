<?php
use fay\helpers\Html;
?>
<div class="col-2-2">
	<div class="col-2-2-body-sidebar">
		<h3>Tables</h3>
		<ul class="table-list">
		<?php foreach($tables as $t){
			$t_name = preg_replace("/^{$prefix}(.*)/", '$1', array_shift($t), 1);
			if(strpos($t_name, '_') &&
				in_array(substr($t_name, 0, strpos($t_name, '_')), $apps) &&
				substr($t_name, 0, strpos($t_name, '_')) != APPLICATION){
				continue;
			}
		?>
			<li class="">
				<?php echo Html::link($t_name, array('tools/database/export', array(
					't'=>$t_name,
				)))?>
			</li>
		<?php }?>
		</ul>
	</div>
	<div class="col-2-2-body">
		<div class="col-2-2-body-content">
			<form id="form">
				<div class="form-field pb0">
					<label for="seo-title" class="title pb0">Fields</label>
					<?php foreach($fields as $f){
						echo F::form()->inputCheckbox('fields[]', $f['Field'], array('label'=>$f['Field']));
					}?>
				</div>
				<div class="form-field pb0">
					<label for="seo-title" class="title pb0">Order</label>
					<?php foreach($fields as $f){
						echo F::form()->inputRadio('order', $f['Field'], array('label'=>$f['Field']));
					}?>
				</div>
				<div class="form-field">
					<label for="seo-title" class="title pb0">Sort</label>
					<?php
						echo F::form()->inputRadio('sort', 'ASC', array('label'=>'ASC'), true);
						echo F::form()->inputRadio('sort', 'DESC', array('label'=>'DESC'));
					?>
				</div>
				<div class="form-field">
					<a href="javascript:;" id="form-submit" class="btn-1">提交</a>
				</div>
			</form>
			<?php echo Html::textarea('code', $insert, array(
				'style'=>'background:none repeat scroll 0 0 #F9F9F9;font-family:Consolas,Monaco,monospace;width:97%;',
				'rows'=>30,
				'cols'=>70,
				'id'=>'code',
			))?>
		</div>
		<div class="clear"></div>
	</div>
</div>