<?php
use fay\models\tables\CatProps;
use fay\helpers\Html;
?>
<div class="box" id="box-props" data-name="props">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>商品属性</h4>
	</div>
	<div class="box-content">
		<?php foreach($props as $p){
			if($p['is_sale_prop'])continue;?>
			<div class="form-field">
				<label class="title bold">
					<?php echo Html::encode($p['title'])?>
					<?php if($p['required']){?>
						<em class="fc-red">(必选)</em>
					<?php }?>
				</label>
				<?php if($p['type'] == CatProps::TYPE_CHECK){//多选?>
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
				}else if($p['type'] == CatProps::TYPE_OPTIONAL){//单选
					echo F::form()->select("cp[{$p['id']}]", Html::getSelectOptions($p['prop_values'], 'id', 'title'));
				}else if($p['type'] == CatProps::TYPE_INPUT){//手工录入
					echo F::form()->inputText("cp_alias[{$p['id']}][0]", array(
						'data-rule'=>'string',
						'data-params'=>'{max:255}',
						'data-label'=>$p['title'].'属性',
						'data-required'=>$p['required'] ? 'required' : false,
					));
					echo Html::inputHidden("cp[{$p['id']}]", 0);
				}else if($p['type'] == CatProps::TYPE_BOOLEAN){//布尔?>
				<div class="goods-prop-box">
					<ul class="goods-prop-list">
					<?php foreach($p['prop_values'] as $pv){?>
						<li>
						<?php 
							echo Html::inputRadio("cp[{$p['id']}][]", $pv['id'], ($p['required'] && $pv['title']) ? true : false, array(
								'id'=>"cp-{$p['id']}-{$pv['id']}",
							));?>
						<label for="<?php echo "cp-{$p['id']}-{$pv['id']}"?>"><?php echo $pv['title'] ? '是' : '否'?></label>
						<?php 
							echo Html::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $pv['title'] ? '是' : '否', array(
								'class'=>'text-short hide',
							));
						?>
						</li>
					<?php }?>
					</ul>
					<div class="clear"></div>
				</div>
				<?php }?>
			</div>
		<?php }?>
	</div>
</div>