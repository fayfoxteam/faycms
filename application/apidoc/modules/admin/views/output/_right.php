<?php
use apidoc\models\tables\Outputs;
?>
<table class="list-table">
	<thead>
		<tr>
			<th>名称</th>
			<th>类型</th>
			<th>描述</th>
			<th>从属</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>名称</th>
			<th>类型</th>
			<th>描述</th>
			<th>从属</th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData(array(
		'type_map'=>Outputs::getTypes(),
	));
?>
	</tbody>
</table>
<?php $listview->showPager();?>