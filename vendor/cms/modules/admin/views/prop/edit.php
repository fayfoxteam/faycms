<?php
use fay\helpers\Html;
use fay\models\tables\CatProps;
?>
<div class="row">
	<div class="col-6">
		<?php include '_right.php'?>
	</div>
	<div class="col-6">
		<form id="edit-prop-form" action="" method="post" class="validform">
			<?php echo F::form()->inputHidden('cat_id')?>
			<div class="form-field">
				<label class="title">属性名</label>
				<?php echo F::form()->inputText('title', array(
					'data-rule'=>'string',
					'data-params'=>'{max:255}',
					'data-label'=>'属性名',
					'data-required'=>'required',
				))?>
			</div>
			<div class="form-field">
				<label class="title">是否为销售属性</label>
				<?php echo F::form()->inputCheckbox('is_sale_prop', 1, array(
					'label'=>'销售属性',
				))?>
				<p class="description">
					销售属性参与SKU计算，即与库存和售价有关
					<br />
					所有销售属性均为<span class="color-red">必选</span>，<span class="color-red">多选</span>属性
				</p>
			</div>
			<div class="form-field">
				<label class="title">是否为必选属性</label>
				<?php echo F::form()->inputCheckbox('required', 1, array(
					'label'=>'必选',
					'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
				))?>
			</div>
			<div class="form-field">
				<label class="title">排序值</label>
				<?php echo F::form()->inputText('sort', array(), 100)?>
				<p class="description">0-255之间的整数，数字越小，排序越靠前</p>
			</div>
			<div class="form-field">
				<label class="title">录入方式</label>
				<?php echo F::form()->inputRadio('type', CatProps::TYPE_CHECK, array(
					'label'=>'多选',
				))?>
				<?php echo F::form()->inputRadio('type', CatProps::TYPE_OPTIONAL, array(
					'label'=>'单选',
					'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
				))?>
				<?php echo F::form()->inputRadio('type', CatProps::TYPE_INPUT, array(
					'label'=>'手工录入',
					'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
				))?>
				<?php echo F::form()->inputRadio('type', CatProps::TYPE_BOOLEAN, array(
					'label'=>'布尔属性',
					'title'=>'一种特殊的单选属性，统一为是否两个属性值',
				))?>
			</div>
			<div class="form-field <?php if(F::form()->getData('type') == CatProps::TYPE_INPUT
				|| F::form()->getData('type') == CatProps::TYPE_BOOLEAN)echo 'hide';?>"
				id="prop-values-container">
				<label class="title">属性值</label>
				<?php echo F::form()->inputText('', array(
					'id'=>'prop-title',
				))?>
				<a href="javascript:;" class="btn-4" id="add-prop-value-link">添加</a>
				<span class="color-grey">（添加后可拖拽排序）</span>
				<div class="dragsort-list" id="prop-list">
				<?php foreach($prop_values as $pv){?>
					<div class="dragsort-item">
						<?php echo Html::inputHidden('old_prop_value_ids[]', $pv['id'])?>
						<a class="dragsort-rm" href="javascript:;"></a>
						<a class="dragsort-item-selector"></a>
						<div class="dragsort-item-container">
							<?php echo F::form()->inputText("prop_values[{$pv['id']}]", array(
								'data-rule'=>'string',
								'data-params'=>'{max:255}',
								'data-label'=>'属性值',
								'data-required'=>'required',
							), $pv['title'])?>
						</div>
					</div>
				<?php }?>
				</div>
			</div>
			<div class="form-field">
				<a href="javascript:;" class="btn" id="edit-prop-form-submit">编辑属性</a>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$("#edit-prop-form").submit(function(){
		if($("input[name='type']:checked").val() == <?php echo CatProps::TYPE_BOOLEAN?>){
			//若是布尔属性，强制将属性值置为0,1两个值
			<?php if($prop['type'] == CatProps::TYPE_BOOLEAN){?>
				var html = '';
				<?php foreach($prop_values as $pv){?>
					html += '<?php echo F::form()->inputText("prop_values[{$pv['id']}]", array(), $pv['title'])?>';
					html += '<?php echo Html::inputHidden('old_prop_value_ids[]', $pv['id'])?>';
				<?php }?>
			<?php }else{?>
				var html = '<input type="hidden" value="1" name="prop_values[]" />';
				html += '<input type="hidden" value="0" name="prop_values[]" />';
			<?php }?>
			$("#prop-list").html(html);
		}else if($("input[name='type']:checked").val() == <?php echo CatProps::TYPE_INPUT?>){
			$("#prop-list").html("");
		}
	});

	$("#add-prop-value-link").click(function(){
		if($("#prop-title").val() == ""){
			alert('属性值不能为空！');
			return false;
		}
		$('#prop-list').append(['<div class="dragsort-item hide">',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<input type="text" name="prop_values[]" value="'+system.encode($("#prop-title").val())+'" data-label="属性值" data-rule="string" data-params="{max:255}" />',
			'</div>',
		'</div>'].join(''));
		$('#prop-list .dragsort-item:last').fadeIn();
		$("#prop-title").val("");
	});

	$("input[name='is_sale_prop']").change(function(){
		if($(this).attr("checked")){
			$("input[name='required']").attr("checked", "checked").attr("disabled", "disabled");
			$("input[name='type'][value='<?php echo CatProps::TYPE_CHECK?>']").attr("checked", "checked");
			$("input[name='type'][value!='<?php echo CatProps::TYPE_CHECK?>']").attr("disabled", "disabled");
			$("#prop-values-container").show();
		}else{
			$("input[name='required']").removeAttr("disabled");
			$("input[name='type'][value!='<?php echo CatProps::TYPE_CHECK?>']").removeAttr("disabled");
		}
	});

	$("input[name='type']").change(function(){
		if($(this).val() == <?php echo CatProps::TYPE_INPUT?> ||
			$(this).val() == <?php echo CatProps::TYPE_BOOLEAN?>){
			$("#prop-values-container").hide();
		}else{
			$("#prop-values-container").show();
		}
	});

	$(".tag-sort").feditsort({
		'url':system.url("admin/prop/sort")
	});
});
</script>