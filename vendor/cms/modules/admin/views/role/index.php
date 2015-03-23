<div class="col-1">
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th class="wp30">角色</th>
				<th>分类</th>
				<th class="w30">可见</th>
				<th>描述</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>角色</th>
				<th>分类</th>
				<th>可见</th>
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
</div>