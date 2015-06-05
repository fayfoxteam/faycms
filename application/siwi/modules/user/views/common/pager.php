<div class="pagenav">
	<span class="pages">
		Page
		<?php echo $listview->current_page?>
		of
		<?php echo $listview->total_pages?>
	</span>
	<?php for($i = 1; $i <= $listview->total_pages; $i++){?>
		<?php if($i == $listview->current_page){?>
			<span class="current"><?php echo $i?></span>
		<?php }else{?>
			<a href="<?php echo $listview->reload?>?page=<?php echo $i?>" class="page"><?php echo $i?></a>
		<?php }?>
	<?php }?>
	<?php if($listview->current_page < $listview->total_pages){?>
		<a href="<?php echo $listview->reload?>?page=<?php echo $listview->current_page + 1?>" class="nextpostslink">&raquo;</a>
	<?php }?>
</div>