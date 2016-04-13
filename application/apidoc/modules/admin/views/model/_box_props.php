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
			<div class="dragsort-item">
				<input type="hidden" name="prop[][name]" value="" />
				<input type="hidden" name="prop[][model]" value="" />
				<input type="hidden" name="prop[][is_array]" value="" />
				<input type="hidden" name="prop[][description]" value="" />
				<input type="hidden" name="prop[][sample]" value="" />
				<input type="hidden" name="prop[][since]" value="" />
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25"><strong>post</strong></span>
					<span class="ib wp15">Post []</span>
					<span class="ib">zsxcv</span>
				</div>
			</div>
		<?php if(isset($props)){?>
			<?php foreach($props as $p){?>
			<div class="dragsort-item">
				<?php
					echo Html::inputHidden("prop[{$p['id']}][name]", $p['name'], array(
						'class'=>'input-name',
					));
					echo Html::inputHidden("prop[{$p['id']}][model]", $p['model'], array(
						'class'=>'input-model',
					));
					echo Html::inputHidden("prop[{$p['id']}][is_array]", $p['is_array'], array(
						'class'=>'input-is-array',
					));
					echo Html::inputHidden("prop[{$p['id']}][description]", $p['description'], array(
						'class'=>'input-description',
					));
					echo Html::inputHidden("prop[{$p['id']}][sample]", $p['sample'], array(
						'class'=>'input-sample',
					));
					echo Html::inputHidden("prop[{$p['id']}][since]", $p['since'], array(
						'class'=>'input-since',
					));
				?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25"><strong><?php echo Html::encode($p['name'])?></strong></span>
					<span class="ib wp15"><?php
						echo Html::encode($p['model_name']);
						if($p['is_array']){
							echo ' []';
						}
					?></span>
					<span><?php echo Html::encode($p['description'])?></span>
				</div>
			</div>
			<?php }?>
		<?php }?>
		</div>
	</div>
</div>