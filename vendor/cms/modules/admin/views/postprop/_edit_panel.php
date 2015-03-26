<?php
use fay\models\tables\Props;
use fay\helpers\Html;
?>
<?php echo F::form()->inputHidden('refer')?>
<div class="form-field">
	<label class="title">属性名称</label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title">属性别名</label>
	<?php echo F::form()->inputText('alias', array(
		'class'=>'form-control mw200',
	))?>
	<p class="description">特殊属性可能需要通过别名调用，可留空</p>
</div>
<div class="form-field">
	<label class="title">是否为必选属性</label>
	<?php echo F::form()->inputCheckbox('required', 1, array(
		'label'=>'必选',
	))?>
</div>
<div class="form-field">
	<label class="title">排序值</label>
	<?php echo F::form()->inputText('sort', array(
		'class'=>'form-control mw150',
	), 100)?>
</div>
<div class="form-field">
	<label class="title">类型</label>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_TEXT, array(
		'label'=>'输入框',
	), true)?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_RADIO, array(
		'label'=>'单选框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_SELECT, array(
		'label'=>'下拉框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_CHECKBOX, array(
		'label'=>'多选框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_TEXTAREA, array(
		'label'=>'文本域',
	))?>
</div>
<div class="form-field <?php if(empty($prop['element']) || !in_array($prop['element'], array(
	Props::ELEMENT_RADIO,
	Props::ELEMENT_SELECT,
	Props::ELEMENT_CHECKBOX,
))) echo 'hide';?>" id="prop-values-container">
	<label class="title">属性值</label>
	<?php echo F::form()->inputText('', array(
		'id'=>'prop-title',
		'class'=>'form-control w200 ib',
	))?>
	<a href="javascript:;" class="btn btn-sm btn-grey" id="add-prop-value-link">添加</a>
	<span class="fc-grey">（添加后可拖拽排序）</span>
	<div class="dragsort-list" id="prop-list">
	<?php if(isset($prop['values']) && is_array($prop['values'])){?>
		<?php foreach($prop['values'] as $pv){?>
			<div class="dragsort-item">
				<?php echo Html::inputHidden('ids[]', $pv['id'])?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<?php echo F::form()->inputText("prop_values[]", array(
						'data-rule'=>'string',
						'data-params'=>'{max:255}',
						'data-label'=>'属性值',
						'class'=>'form-control',
					), $pv['title'])?>
				</div>
			</div>
		<?php }?>
	<?php }?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$('#add-prop-value-link').on('click', function(){
		if($('#prop-title').val() == ''){
			alert('属性值不能为空！');
			return false;
		}
		$('#prop-list').append(['<div class="dragsort-item hide">',
			'<input type="hidden" name="ids[]" value="" />',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<input type="text" name="prop_values[]" value="'+system.encode($("#prop-title").val())+'" data-label="属性值" data-rule="string" data-params="{max:255}" class="form-control" />',
			'</div>',
		'</div>'].join(''));
		$('#prop-list .dragsort-item:last').fadeIn();
		$('#prop-title').val('');
	});

	$('input[name="element"]').change(function(){
		if($(this).val() == <?php echo Props::ELEMENT_RADIO?> ||
			$(this).val() == <?php echo Props::ELEMENT_SELECT?> ||
			$(this).val() == <?php echo Props::ELEMENT_CHECKBOX?>){
			$('#prop-values-container').show();
		}else{
			$('#prop-values-container').hide();
		}
	});

	$('.edit-sort').feditsort({
		'url':system.url('admin/post-prop/sort')
	});
});
</script>