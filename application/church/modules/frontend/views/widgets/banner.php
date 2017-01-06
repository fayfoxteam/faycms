<?php
use fay\services\FileService;
use fay\helpers\Html;
?>
<div class="page-banner" 
<?php if(isset($files[0]['file_id'])){?>
	style="background-image:url(<?php echo FileService::service()->getUrl($files[0]['file_id'], FileService::PIC_RESIZE, array(
		'dh'=>443,
		'dw'=>1920,
	))?>)"
<?php }?>
>
	<div class="page-title-container">
		<h1 class="page-title"><?php echo Html::encode(F::app()->layout->page_title)?></h1>
	</div>
</div>