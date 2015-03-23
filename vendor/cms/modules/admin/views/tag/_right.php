<?php
use cms\helpers\ListTableHelper;
?>
<table border="0" cellpadding="0" cellspacing="0" class="list-table tags">
	<thead>
		<tr>
			<th>名称</th>
			<th width="15%"><?php echo ListTableHelper::getSortLink('count', '文章数')?></th>
			<th class="w70"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>名称</th>
			<th><?php echo ListTableHelper::getSortLink('count', '文章数')?></th>
			<th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$listview->showData();
?>
	</tbody>
</table>
<?php $listview->showPager();?>