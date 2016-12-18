<?php
use cms\helpers\ListTableHelper;

/**
 * @var $listview \fay\common\ListView
 */
?>
<table class="list-table tags">
	<thead>
		<tr>
			<th>名称</th>
			<th><?php echo ListTableHelper::getSortLink('posts', '文章数')?></th>
			<th><?php echo ListTableHelper::getSortLink('feeds', '动态数')?></th>
			<th class="w90"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>名称</th>
			<th><?php echo ListTableHelper::getSortLink('posts', '文章数')?></th>
			<th><?php echo ListTableHelper::getSortLink('feeds', '动态数')?></th>
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