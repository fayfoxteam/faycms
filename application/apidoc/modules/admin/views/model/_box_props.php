<?php
use fay\helpers\Html;
?>
<div class="box" id="box-props" data-name="props">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>属性</h4>
	</div>
	<div class="box-content">
		<div class="cf mt5">
			<?php echo Html::link('新增属性', '#add-prop-dialog', array(
				'class'=>'btn',
				'id'=>'add-prop-link',
			))?>
		</div>
		<div class="dragsort-list" id="model-list">
		<?php if(isset($props)){?>
			<?php foreach($props as $p){?>
			<div class="dragsort-item" id="model-<?php echo $p['id']?>">
				<?php
					echo Html::inputHidden("props[{$p['id']}][name]", $p['name'], array(
						'class'=>'input-name',
					));
					echo Html::inputHidden("props[{$p['id']}][type_name]", $p['type_name'], array(
						'class'=>'input-model',
					));
					echo Html::inputHidden("props[{$p['id']}][is_array]", $p['is_array'], array(
						'class'=>'input-is-array',
					));
					echo Html::inputHidden("props[{$p['id']}][description]", $p['description'], array(
						'class'=>'input-description',
					));
					echo Html::inputHidden("props[{$p['id']}][sample]", $p['sample'], array(
						'class'=>'input-sample',
					));
					echo Html::inputHidden("props[{$p['id']}][since]", $p['since'], array(
						'class'=>'input-since',
					));
				?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25">
						<strong><?php echo Html::encode($p['name'])?></strong>
						<p><?php
							echo Html::link('编辑', '#edit-prop-dialog', array(
								'class'=>'edit-prop-link',
							));
						?></p>
					</span>
					<span class="ib wp15 vat"><?php
						echo Html::encode($p['type_name']);
						if($p['is_array']){
							echo ' []';
						}
					?></span>
					<span class="vat"><?php echo Html::encode($p['description'])?></span>
				</div>
			</div>
			<?php }?>
		<?php }?>
		</div>
	</div>
</div>
<script>
$(function(){
	model.validProp(<?php echo json_encode(F::form('prop')->getJsRules())?>, <?php echo json_encode(F::form('prop')->getLabels())?>);
});
</script>