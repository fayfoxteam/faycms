<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
	<div class="col-12">
		<table class="list-table form-inline">
			<thead>
			<tr>
				<th>名称</th>
				<th>支付代码</th>
				<th class="wp10">是否启用</th>
				<th>创建时间</th>
				<th>最后修改时间</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th>名称</th>
				<th>支付代码</th>
				<th>是否启用</th>
				<th>创建时间</th>
				<th>最后修改时间</th>
			</tr>
			</tfoot>
			<tbody><?php $listview->showData()?></tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12"><?php $listview->showPager();?></div>
</div>