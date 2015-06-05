<form method="post" id="form" class="validform">
	<?php include '_edit_panel.php';?>
	<div class="form-options clearfix">
		<a href="javascript:;" id="form-submit" class="btn-red fr">确认发布</a>
	</div>
</form>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>static/siwi/js/editor.js"></script>
<script>
editor.tag();
editor.cats();
editor.uploadFile();
editor.removeFile();
editor.uploadThumbnail();
editor.uploadFiles();
editor.events();
</script>