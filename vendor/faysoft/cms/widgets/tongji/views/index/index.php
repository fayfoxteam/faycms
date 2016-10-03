<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>访问统计（概况）</h4>
	</div>
	<div class="box-content">
		<table class="inbox-table">
			<thead>
				<tr>
					<th></th>
					<th>PV</th>
					<th>UV</th>
					<th>IP</th>
					<th>新访客</th>
					<th>跳出率</th>
				</tr>
			</thead>
			<tbody>
				<tr class="order-desc">
					<th>今天</th>
					<td><?php echo $today['pv']?></td>
					<td><?php echo $today['uv']?></td>
					<td><?php echo $today['ip']?></td>
					<td><?php echo $today['new_visitors']?></td>
					<td><?php echo $today['bounce_rate']?>%</td>
				</tr>
				<tr class="order-desc">
					<th>昨天</th>
					<td><?php echo $yesterday['pv']?></td>
					<td><?php echo $yesterday['uv']?></td>
					<td><?php echo $yesterday['ip']?></td>
					<td><?php echo $yesterday['new_visitors']?></td>
					<td><?php echo $yesterday['bounce_rate']?>%</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>