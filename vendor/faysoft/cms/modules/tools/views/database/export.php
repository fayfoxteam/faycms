<?php
use fay\helpers\HtmlHelper;
?>
<div class="row">
	<div class="col-9">
		<div class="col-2-2-body-content">
			<form id="form">
                <?php echo F::form()->inputHidden('t')?>
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
					<a href="javascript:;" id="form-submit" class="btn">提交</a>
				</div>
			</form>
			<?php echo HtmlHelper::textarea('code', $insert, array(
				'style'=>'font-family:Consolas,Monaco,monospace',
				'class'=>'form-control autosize h200',
				'id'=>'code',
			))?>
		</div>
	</div>
	<div class="col-3">
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
			<li class="<?php if(F::input()->get('t') == $t_name)echo 'bold'?>">
				<?php echo HtmlHelper::link($t_name, array('cms/tools/database/export', array(
					't'=>$t_name,
				)))?>
			</li>
		<?php }?>
		</ul>
	</div>
</div>