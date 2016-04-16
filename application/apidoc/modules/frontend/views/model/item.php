<?php
use fay\helpers\StringHelper;
use fay\helpers\Html;
use apidoc\helpers\TrackHelper;
?>
<div class="panel panel-headerless">
	<div class="panel-body"><?php
		echo StringHelper::nl2p($model['description']);
	?></div>
</div>
<div class="panel">
	<div class="panel-header"><h2>数据字典</h2></div>
	<div class="panel-body">
		<table>
			<thead>
				<tr>
					<th width="25%">名称</th>
					<th width="20%">类型</th>
					<th width="15%">示例值</th>
					<th width="40%">描述</th>
				</tr>
			</thead>
			<tbody>
			<?php $track_models = TrackHelper::getTrackModels()?>
			<?php foreach($properties as $p){?>
				<tr>
					<td><?php echo Html::encode($p['name'])?></td>
					<td><?php
						if($p['type'] >= 1000 && !in_array($p['type'], $track_models)){
							//对象类型特殊处理
							echo Html::link($p['model_name'], array(
								'model/' . $p['type'], array(
									'trackid'=>TrackHelper::getTrackId(),
								), false
							));
						}else{
							echo Html::encode($p['model_name']);
						}
						if($p['is_array']){
							echo ' []';
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
<div class="panel">
	<div class="panel-header"><h2>示例值</h2></div>
	<div class="panel-body">
	<?php if($model['sample']){?>
		<pre id="sample_response" class="jsonview"><?php
			echo Html::encode($model['sample']);
		?></pre>
	<?php }else{?>
		<span>无</span>
	<?php }?>
	</div>
</div>