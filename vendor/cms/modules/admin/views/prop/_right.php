<table border="0" cellpadding="0" cellspacing="0" class="list-table props">
	<thead>
		<tr>
			<th>属性名</th>
			<th>必选</th>
			<th>销售属性</th>
			<th class="w70">排序值</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>属性名</th>
			<th>必选</th>
			<th>销售属性</th>
			<th>排序值</th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData();
?>
	</tbody>
</table>
<?php $listview->showPage();?>