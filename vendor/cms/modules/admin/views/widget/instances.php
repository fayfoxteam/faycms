<div class="row">
	<div class="col-12">
		<table class="list-table">
			<thead>
				<tr>
					<th>描述</th>
					<th>别名</th>
					<th class="w50">启用</th>
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
	</div>
</div>