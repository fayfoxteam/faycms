<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<table class="list-table props">
	<thead>
		<tr>
			<th>属性名称</th>
			<th>类型</th>
			<th>必选</th>
			<th>可见</th>
			<th class="w70">排序值</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>属性名称</th>
			<th>类型</th>
			<th>必选</th>
			<th>可见</th>
			<th>排序值</th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData();
?>
	</tbody>
</table>
<?php $listview->showPager();?>