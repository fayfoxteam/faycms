<table class="list-table">
	<thead>
		<tr>
			<th>名称</th>
			<th>链接地址</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>名称</th>
			<th>链接地址</th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData();
?>
	</tbody>
</table>
<?php $listview->showPager();?>