<div class="col-1">
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th>描述</th>
				<th>别名</th>
				<th class="w35">启用</th>
				<th>小工具标题</th>
				<th>小工具名称</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>描述</th>
				<th>别名</th>
				<th>启用</th>
				<th>小工具标题</th>
				<th>小工具名称</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		$listview->showData();
	?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div><?php
