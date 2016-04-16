<?php
use fay\helpers\Html;
use apidoc\models\tables\Inputs;

$type_map = Inputs::getTypes();
?>
<div class="box" id="box-inputs" data-name="inputs">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>请求参数</h4>
	</div>
	<div class="box-content">
		<div>
			<?php echo Html::link('添加请求参数', '#add-input-parameter-dialog', array(
				'class'=>'btn ',
				'id'=>'add-input-parameter-link',
				'title'=>false,
			))?>
		</div>
		<table class="list-table" id="input-parameter-table">
			<thead>
				<tr>
					<th>名称</th>
					<th>类型</th>
					<th>必选</th>
					<th>自从</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody><?php if(!empty($inputs)){?>
			<?php foreach($inputs as $i){?>
				<tr valign="top" id="old-<?php echo $i['id']?>">
					<td>
						<?php
							echo Html::inputHidden("inputs[{$i['id']}][name]", $i['name'], array(
								'class'=>'input-name',
							));
							echo Html::inputHidden("inputs[{$i['id']}][type]", $i['type'], array(
								'class'=>'input-type',
							));
							echo Html::inputHidden("inputs[{$i['id']}][required]", $i['required'], array(
								'class'=>'input-required',
							));
							echo Html::inputHidden("inputs[{$i['id']}][description]", $i['description'], array(
								'class'=>'input-description',
							));
							echo Html::inputHidden("inputs[{$i['id']}][sample]", $i['sample'], array(
								'class'=>'input-sample',
							));
							echo Html::inputHidden("inputs[{$i['id']}][since]", $i['since'], array(
								'class'=>'input-since',
							));
						?>
						<strong><?php echo Html::encode($i['name'])?></strong>
						<div class="row-actions"><?php
							echo Html::link('编辑', '#edit-input-parameter-dialog', array(
								'class'=>'edit-input-parameter-link',
							));
							echo Html::link('删除', 'javascript:;', array(
								'class'=>'fc-red remove-input-parameter-link',
							));
						?></div>
					</td>
					<td><?php echo $type_map[$i['type']]?></td>
					<td><?php echo $i['required'] ? '<span class="fc-green">是</span>' : '否'?></td>
					<td><?php echo Html::encode($i['since'])?></td>
					<td><?php echo Html::encode($i['description'])?></td>
				</tr>
			<?php }?>
			<?php }?></tbody>
		</table>
	</div>
</div>
<script>
$(function(){
	api.inputTypeMap = <?php echo json_encode($type_map)?>;
	api.validInputParameter(<?php echo json_encode(F::form('input-parameter')->getJsRules())?>, <?php echo json_encode(F::form('input-parameter')->getLabels())?>);
});
</script>