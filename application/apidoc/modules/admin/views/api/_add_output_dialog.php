<?php
use fay\helpers\Html;
?>
<div class="hide">
	<div id="add-output-dialog" class="dialog">
		<div class="dialog-content w600">
			<h4>添加响应参数</h4>
			<form class="output-form" id="add-output-form">
				<table class="form-table">
					<tr>
						<th class="adaption">名称<em class="required">*</em></th>
						<td><?php echo F::form('output')->inputText('name', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">类型<em class="required">*</em></th>
						<td><?php echo F::form('output')->inputText('model_name', array(
							'class'=>'form-control',
							'id'=>'add-output-model-name',
							'data-ajax-param-name'=>'name',
						), 'String');?></td>
					</tr>
					<tr>
						<th class="adaption">是否数组<em class="required">*</em></th>
						<td><?php
							echo F::form('output')->inputRadio('is_array', 1, array(
								'label'=>'是',
							));
							echo F::form('output')->inputRadio('is_array', 0, array(
								'label'=>'否',
							), true);
						?></td>
					</tr>
					<tr>
						<th class="adaption">描述</th>
						<td><?php echo F::form('output')->textarea('description', array(
							'class'=>'form-control h60 autosize',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">示例值</th>
						<td><?php echo F::form('output')->textarea('sample', array(
							'class'=>'form-control h60 autosize',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">自从</th>
						<td><?php echo F::form('output')->inputText('since', array(
							'class'=>'form-control w150 ib',
						))?></td>
					</tr>
					<tr>
						<th class="adaption"></th>
						<td><?php
							echo Html::link('添加', 'javascript:;', array(
								'class'=>'btn mr10',
								'id'=>'add-output-form-submit',
							));
							echo Html::link('取消', 'javascript:;', array(
								'class'=>'btn btn-grey fancybox-close',
							));
						?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>