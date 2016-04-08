<?php
use fay\core\Loader;
use fay\helpers\Html;
use apidoc\models\tables\Inputs;
use apidoc\models\tables\Apis;
use apidoc\helpers\ApiHelper;
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
			<div class="col-10 pt7"><?php
				$http_mothods_map = Apis::getHttpMethods();
				echo strtoupper($http_mothods_map[$api['api']['http_method']]);
			?></div>
		</div>
		<div class="form-group">
			<label class="col-2">是否需要登录</label>
			<div class="col-10 pt7"><?php
				echo $api['api']['need_login'] ? '<span class="required">是</span>' : '否';
			?></div>
		</div>
		<div class="form-group">
			<label class="col-2">状态</label>
			<div class="col-10 pt7"><?php echo ApiHelper::getStatus($api['api']['status'])?></div>
		</div>
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
					<th>名称</th>
					<th>类型</th>
					<th>是否必须</th>
					<th>示例值</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
			<?php $type_map = Inputs::getTypes();?>
			<?php foreach($api['inputs'] as $input){?>
				<tr>
					<td><?php echo $input['name']?></td>
					<td><?php echo $type_map[$input['type']]?></td>
					<td><?php echo $input['required'] ? '<span class="required">是</span>' : '否'?></td>
					<td><?php echo Html::encode($input['sample'])?></td>
					<td><?php echo Html::encode($input['description'])?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><h2>响应参数</h2></div>
	<div class="panel-body"><?php
		
	?></div>
</div>
<div class="panel">
	<div class="panel-header"><h2>响应示例</h2></div>
	<div class="panel-body">
	<?php if($api['api']['sample_response']){?>
		<pre class="prettyprint json"><code><?php
			echo $api['api']['sample_response'];
		?></code></pre>
	<?php }else{?>
		<span>无</span>
	<?php }?>
	</div>
</div>