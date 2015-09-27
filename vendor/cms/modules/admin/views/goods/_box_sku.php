<?php
use fay\helpers\Html;
?>
<div class="box" id="box-sku" data-name="sku">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>SKU</h4>
	</div>
	<div class="box-content">
		<?php foreach($props as $p){
			if(!$p['is_sale_prop'])continue;?>
			<div class="sku-group form-field" data-name="<?php echo $p['title']?>" data-pid="<?php echo $p['id']?>">
				<label class="sku-label title"><?php echo $p['title']?>：</label>
				<div class="sku-box">
					<ul class="sku-list">
					<?php foreach($p['prop_values'] as $pv){?>
						<li class="sku-item">
							<?php echo F::form()->inputCheckbox("cp_sale[{$p['id']}][]", $pv['id'], array(
								'id'=>"cp_sale_{$p['id']}_{$pv['id']}",
								'data-rule'=>'string',
								'data-params'=>'{max:255}',
								'data-label'=>$p['title'].'属性',
								'data-required'=>$p['required'] ? 'required' : false,
							))?>
							<label for="<?php echo "cp_sale_{$p['id']}_{$pv['id']}"?>"><?php echo $pv['title']?></label>
							<?php echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $pv['title'], array(
								'class'=>'text-short hide',
							))?>
						</li>
					<?php }?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		<?php }?>
		
		<div id="sku-table-container"></div>
	</div>
</div>