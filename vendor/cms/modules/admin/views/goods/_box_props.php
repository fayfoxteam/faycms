<?php
use fay\models\tables\GoodsCatProps;
use fay\helpers\Html;
?>
<div class="box" id="box-props" data-name="props">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>商品属性</h4>
	</div>
	<div class="box-content"><?php
		if($props){
			foreach($props as $p){
				if($p['is_sale_prop'])continue;?>
				<div class="form-field">
					<label class="title bold">
						<?php echo Html::encode($p['title'])?>
						<?php if($p['required']){?>
							<em class="fc-red">(必选)</em>
						<?php }?>
					</label>
					<?php if($p['type'] == GoodsCatProps::TYPE_CHECK){//多选?>
					<div class="goods-prop-box">
						<ul class="goods-prop-list">
						<?php foreach($p['prop_values'] as $pv){?>
							<li>
							<?php 
							echo F::form()->inputCheckbox("cp[{$p['id']}][]", $pv['id'], array(
								'id'=>"cp-{$p['id']}-{$pv['id']}",
								'data-rule'=>'int',
								'data-label'=>$p['title'].'属性',
								'data-required'=>$p['required'] ? 'required' : false,
							));?>
							<label for="<?php echo "cp-{$p['id']}-{$pv['id']}"?>"><?php echo $pv['title']?></label>
							<?php 
							echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $pv['title'], array(
								'class'=>'text-short hide',
							));
							?>
							</li>
						<?php }?>
						</ul>
						<div class="clear"></div>
					</div>
					<?php 
					}else if($p['type'] == GoodsCatProps::TYPE_OPTIONAL){//单选
						echo F::form()->select("cp[{$p['id']}]", Html::getSelectOptions($p['prop_values']));
					}else if($p['type'] == GoodsCatProps::TYPE_INPUT){//手工录入
						echo F::form()->inputText("cp_alias[{$p['id']}][0]", array(
							'data-rule'=>'string',
							'data-params'=>'{max:255}',
							'data-label'=>$p['title'].'属性',
							'data-required'=>$p['required'] ? 'required' : false,
						));
						echo Html::inputHidden("cp[{$p['id']}]", 0);
					}?>
				</div>
			<?php }?>
		<?php }else{?>
			该商品无可选属性
		<?php }?>
	</div>
</div>