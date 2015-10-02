<table class="list-table">
	<thead>
		<tr>
			<th>属性名称</th>
			<th>必选</th>
			<th>销售属性</th>
			<th class="w90">排序值</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>属性名称</th>
			<th>必选</th>
			<th>销售属性</th>
			<th>排序值</th>
		</tr>
	</tfoot>
	<tbody>
		<?php $listview->showData();?>
	</tbody>
</table>
<?php $listview->showPager();?>