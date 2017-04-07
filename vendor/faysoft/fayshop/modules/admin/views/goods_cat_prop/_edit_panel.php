<?php
use fay\helpers\HtmlHelper;
use fayshop\models\tables\GoodsCatPropsTable;
?>
<?php echo F::form()->inputHidden('refer')?>
<?php echo F::form()->inputHidden('cat_id')?>
<div class="form-field">
	<label class="title bold">属性名称</label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title bold">属性别名</label>
	<?php echo F::form()->inputText('alias', array(
		'class'=>'form-control mw200',
	))?>
	<p class="description">特殊属性可能需要通过别名调用，可留空</p>
</div>
<div class="form-field">
	<label class="title bold">是否为销售属性</label>
	<?php echo F::form()->inputCheckbox('is_sale_prop', 1, array(
		'label'=>'销售属性',
	))?>
	<p class="description">
		销售属性参与SKU计算，即与库存和售价有关
		<br />
		所有销售属性均为<span class="fc-red">必选</span>，<span class="fc-red">多选</span>属性
	</p>
</div>
<div class="form-field">
	<label class="title bold">是否为必选属性</label>
	<?php echo F::form()->inputCheckbox('required', 1, array(
		'label'=>'必选',
		'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
	))?>
</div>
<div class="form-field">
	<label class="title bold">排序值</label>
	<?php echo F::form()->inputText('sort', array(
		'class'=>'form-control w90 ib',
	), 100)?>
	<p class="description">0-255之间的整数，数字越小，排序越靠前</p>
</div>
<div class="form-field">
	<label class="title bold">录入方式</label>
	<?php echo F::form()->inputRadio('type', GoodsCatPropsTable::TYPE_CHECK, array(
		'label'=>'多选',
	), true)?>
	<?php echo F::form()->inputRadio('type', GoodsCatPropsTable::TYPE_OPTIONAL, array(
		'label'=>'单选',
		'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
	))?>
	<?php echo F::form()->inputRadio('type', GoodsCatPropsTable::TYPE_INPUT, array(
		'label'=>'手工录入',
		'disabled'=>F::form()->getData('is_sale_prop') ? 'disabled' : false,
	))?>
</div>
<div class="form-field <?php if(F::form()->getData('type') == GoodsCatPropsTable::TYPE_INPUT)echo 'hide';?>"
	id="prop-values-container">
	<label class="title bold">属性值</label>
	<?php echo F::form()->inputText('', array(
		'class'=>'form-control w200 ib',
		'id'=>'prop-title',
	))?>
	<a href="javascript:;" class="btn btn-grey btn-sm" id="add-prop-value-link">添加</a>
	<span class="fc-grey">（添加后可拖拽排序）</span>
	<div class="dragsort-list" id="prop-list">
	<?php if(!empty($prop_values)){?>
		<?php foreach($prop_values as $pv){?>
			<div class="dragsort-item">
				<?php echo HtmlHelper::inputHidden('old_prop_value_ids[]', $pv['id'])?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<?php echo F::form()->inputText("prop_values[{$pv['id']}]", array(
						'data-rule'=>'string',
						'data-params'=>'{max:255}',
						'data-label'=>'属性值',
						'data-required'=>'required',
						'class'=>'form-control',
					), $pv['title'])?>
				</div>
			</div>
		<?php }?>
	<?php }?>
	</div>
</div>
<div class="form-field">
	<?php echo F::form()->submitLink('提交', array(
		'class'=>'btn',
	))?>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
	$("#edit-prop-form").submit(function(){
		if($("input[name='type']:checked").val() == <?php echo GoodsCatPropsTable::TYPE_INPUT?>){
			$("#prop-list").html("");
		}
	});

	$("#add-prop-value-link").click(function(){
		if($("#prop-title").val() == ""){
			common.alert('属性值不能为空！');
			return false;
		}
		$('#prop-list').append(['<div class="dragsort-item hide">',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<input type="text" name="prop_values[]" value="'+system.encode($("#prop-title").val())+'" data-label="属性值" data-rule="string" data-params="{max:255}" class="form-control" />',
			'</div>',
		'</div>'].join(''));
		$('#prop-list .dragsort-item:last').fadeIn();
		$("#prop-title").val("");
	});

	$("input[name='is_sale_prop']").change(function(){
		if($(this).attr("checked")){
			$("input[name='required']").attr("checked", "checked").attr("disabled", "disabled");
			$("input[name='type'][value='<?php echo GoodsCatPropsTable::TYPE_CHECK?>']").attr("checked", "checked");
			$("input[name='type'][value!='<?php echo GoodsCatPropsTable::TYPE_CHECK?>']").attr("disabled", "disabled");
			$("#prop-values-container").show();
		}else{
			$("input[name='required']").removeAttr("disabled");
			$("input[name='type'][value!='<?php echo GoodsCatPropsTable::TYPE_CHECK?>']").removeAttr("disabled");
		}
	});

	$("input[name='type']").change(function(){
		if($(this).val() == <?php echo GoodsCatPropsTable::TYPE_INPUT?>){
			$("#prop-values-container").hide();
		}else{
			$("#prop-values-container").show();
		}
	});

	$('.edit-sort').feditsort({
		'url':system.url("fayshop/admin/goods-cat-prop/sort")
	});
});
</script>