<?php
use apidoc\models\tables\Outputs;
?>
<div class="form-field">
	<label class="title bold">名称<em class="required">*</em></label>
	<?php echo F::form()->inputText('name', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title bold">类型</label>
	<?php echo F::form('input-parameter')->select('type', Outputs::getTypes(), array(
		'class'=>'form-control w150 ib',
	), Outputs::TYPE_STRING)?>
</div>
<div class="form-field">
	<label class="title bold">描述</label>
	<?php echo F::form()->textarea('description', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>
<div class="form-field">
	<label class="title bold">示例值</label>
	<?php echo F::form()->textarea('sample', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>
<div class="form-field">
	<label class="title bold">自从</label>
	<?php echo F::form()->inputText('since', array(
		'class'=>'form-control w150 ib',
	))?>
</div>
<div class="form-field">
	<label class="title bold">从属</label>
	<?php echo F::form()->inputText('parent_output', array(
		'class'=>'form-control',
		'id'=>'parent-output',
	));?>
	<p class="description">
		从属于某个对象
	</p>
</div>
<script type="text/javascript" src="<?php echo $this->appStatic('js/output.js')?>"></script>
<script>
output.init();
</script>