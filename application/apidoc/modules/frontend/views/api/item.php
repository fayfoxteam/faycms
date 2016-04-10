<?php
use fay\core\Loader;
use fay\helpers\Html;
use apidoc\helpers\ApiHelper;
use fay\helpers\StringHelper;
use apidoc\helpers\TrackHelper;
?>
<?php if($api['api']['description']){?>
<div class="panel panel-headerless">
	<div class="panel-body"><?php
		Loader::vendor('Markdown/markdown');
		echo Markdown($api['api']['description']);
	?></div>
</div>
<?php }?>
<div class="panel">
	<div class="panel-header"><h2>请求说明</h2></div>
	<div class="panel-body">
		<div class="form-group">
			<label class="col-2">HTTP请求方式</label>
			<div class="col-10 pt7"><?php echo ApiHelper::getHttpMethod($api['api']['http_method']);?></div>
		</div>
		<div class="form-group-separator"></div>
		<div class="form-group">
			<label class="col-2">是否需要登录</label>
			<div class="col-10 pt7"><?php
				echo $api['api']['need_login'] ? '<span class="required">是</span>' : '否';
			?></div>
		</div>
		<div class="form-group-separator"></div>
		<div class="form-group">
			<label class="col-2">状态</label>
			<div class="col-10 pt7"><?php echo ApiHelper::getStatus($api['api']['status'])?></div>
		</div>
		<div class="form-group-separator"></div>
		<div class="form-group">
			<label class="col-2">自从</label>
			<div class="col-10 pt7"><?php echo $api['api']['since']?></div>
		</div>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><h2>请求参数</h2></div>
	<div class="panel-body">
		<table>
			<thead>
				<tr>
					<th width="22%">名称</th>
					<th width="15%">类型</th>
					<th width="10%">是否必须</th>
					<th width="12%">示例值</th>
					<th width="36%">描述</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($api['inputs'] as $input){?>
				<tr>
					<td><?php echo Html::encode($input['name'])?></td>
					<td><?php echo ApiHelper::getInputType($input['type'])?></td>
					<td><?php echo ApiHelper::getRequired($input['required'])?></td>
					<td><?php echo Html::encode($input['sample'])?></td>
					<td><?php echo $input['description']?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><h2>响应参数</h2></div>
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
			<?php foreach($api['outputs'] as $output){?>
				<tr>
					<td><?php echo Html::encode($output['name'])?></td>
					<td><?php
						if($output['model_id'] >= 1000){
							//对象类型特殊处理
							echo Html::link($output['model_name'], array(
								'model/' . $output['model_id'], array(
									'trackid'=>TrackHelper::getTrackId(),
								), false
							));
						}else{
							echo Html::encode($output['model_name']);
						}
					?></td>
					<td><?php echo Html::encode($output['sample'])?></td>
					<td><?php echo $output['description']?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><h2>响应示例</h2></div>
	<div class="panel-body">
	<?php if($api['api']['sample_response']){?>
		<pre id="sample_response" class="jsonview"><?php
			echo Html::encode($api['api']['sample_response']);
		?></pre>
	<?php }else{?>
		<span>无</span>
	<?php }?>
	</div>
</div>