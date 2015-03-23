<?php
use cms\helpers\ListTableHelper;
?>
<div class="col-1">
	<table border="0" cellpadding="0" cellspacing="0" class="list-table links">
		<thead>
			<tr>
				<th>标题</th>
				<th>URL</th>
				<th class="wp10">可见性</th>
				<th class="w70"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
				<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>标题</th>
				<th>URL</th>
				<th>可见性</th>
				<th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
				<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
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

<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(".edit-sort").feditsort({
	'url':system.url("admin/link/sort")
});
</script>