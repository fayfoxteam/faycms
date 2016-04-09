<?php
use fay\helpers\StringHelper;
use fay\helpers\Html;
use apidoc\helpers\ApiHelper;
?>
<div class="panel panel-headerless">
	<div class="panel-body"><?php
		echo StringHelper::nl2p($output['description']);
	?></div>
</div>
<div class="panel">
	<div class="panel-header"><h2>数据字典</h2></div>
	<div class="panel-body">
		<table>
			<thead>
				<tr>
					<th>名称</th>
					<th>类型</th>
					<th>示例值</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($properties as $p){?>
				<tr>
					<td><?php echo Html::encode($p['name'])?></td>
					<td><?php
						$type = ApiHelper::getOutputType($p['type']);
						if($type == 'Object'){
							//对象类型特殊处理
							echo Html::link(StringHelper::underscore2case($p['name']), array(
								'model/' . $p['id'], array(
									'api_id'=>\F::input()->get('api_id', 'intval', false),
								), false
							));
						}else{
							echo $type;
						}
					?></td>
					<td><?php echo Html::encode($p['sample'])?></td>
					<td><?php echo $p['description']?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>