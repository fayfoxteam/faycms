<div class="col-1">
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th class="wp15">别名</th>
				<th>描述</th>
				<th class="w35">启用</th>
				<th>类型</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>别名</th>
				<th>描述</th>
				<th>启用</th>
				<th>类型</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		$listview->showData();
	?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div>