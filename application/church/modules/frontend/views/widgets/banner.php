<div class="page-banner" 
<?php if(isset($files[0]['file_id'])){?>
	style="background-image:url(<?php echo \fay\services\File::service()->getUrl($files[0]['file_id'])?>)"
<?php }?>
>
	<div class="page-title-container">
		<h1 class="page-title">Post formats</h1>
	</div>
</div>