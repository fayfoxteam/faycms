<table class="list-table">
	<thead>
		<tr>
			<th class="wp38">名称</th>
			<th>描述</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>名称</th>
			<th>描述</th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData();
?>
	</tbody>
</table>
<?php $listview->showPager();?>