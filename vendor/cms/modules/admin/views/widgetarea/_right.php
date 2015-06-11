<table class="list-table">
	<thead>
		<tr>
			<th>别名</th>
			<th>描述</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>别名</th>
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