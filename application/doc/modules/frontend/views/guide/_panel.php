<?php
use fay\helpers\Html;
?>
<div class="panel<?php if(empty($title))echo ' panel-headerless'?>" <?php if(isset($id))echo 'id="part-'.$id.'"'?>>
	<?php if(!empty($title)){?>
	<div class="panel-header">
		<h2><?php
			echo $title;
			if(!empty($file_link)){
				echo Html::link('<i class="icon-file"></i>', 'https://github.com/fayfoxteam/faycms/blob/master/'.$file_link, array(
					'encode'=>false,
					'title'=>'查看代码文件',
					'target'=>'_blank',
					'rel'=>'nofollow',
				));
			}
		?></h2>
	</div>
	<?php }?>
	<div class="panel-body"><?php echo $body?></div>
</div>