<?php
use fay\helpers\Html;
?>
<div class="box" id="box-sku" data-name="sku">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>SKU</h4>
	</div>
	<div class="box-content"><?php
		if($props){
			foreach($props as $p){
				if(!$p['is_sale_prop'])continue;?>
				<div class="sku-group form-field" data-name="<?php echo $p['title']?>" data-pid="<?php echo $p['id']?>">
					<label class="sku-label title bold"><?php echo $p['title']?>：</label>
					<div class="sku-box">
						<?php foreach($p['prop_values'] as $pv){?>
							<p class="ib w240 sku-item">
								<?php echo F::form()->inputCheckbox("cp_sale[{$p['id']}][]", $pv['id'], array(
									'id'=>"cp-sale-{$p['id']}-{$pv['id']}",
									'data-rule'=>'string',
									'data-params'=>'{max:255}',
									'data-label'=>$p['title'].'属性',
									'data-required'=>$p['required'] ? 'required' : false,
								))?>
								<label for="<?php echo "cp-sale-{$p['id']}-{$pv['id']}"?>"><?php echo $pv['title']?></label>
								<?php echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $pv['title'], array(
									'class'=>'form-control mw200 ib fn-hide',
								))?>
							</p>
						<?php }?>
					</div>
				</div>
			<?php }?>
		<?php }else{?>
			该商品无销售属性
		<?php }?>
		<div id="sku-table-container"></div>
	</div>
</div>