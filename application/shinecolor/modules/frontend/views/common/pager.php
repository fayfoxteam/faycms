<?php
use fay\helpers\Html;
?>
<div class="pagenav">
	<span class="pages">
		第
		<?php echo $listview->current_page?>
		/
		<?php echo $listview->total_pages?>
		页
		&nbsp;
	</span>
	<?php for($i = 1; $i <= $listview->total_pages; $i++){?>
		<?php if($i == $listview->current_page){?>
			<span class="current"><?php echo $i?></span>
		<?php }else{
			if($i > 1){
				echo Html::link($i, "{$listview->reload}?page={$i}", array(
					'class'=>'page',
				));
			}else{
				echo Html::link($i, $listview->reload, array(
					'class'=>'page',
				));
			}
		}?>
	<?php }?>
	<?php if($listview->current_page < $listview->total_pages){?>
		<a href="<?php echo $listview->reload?>?page=<?php echo $listview->current_page + 1?>" class="nextpostslink">&raquo;</a>
	<?php }?>
</div>