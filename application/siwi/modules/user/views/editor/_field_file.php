<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label class="title">上传:</label>
		<span class="tip">你可以选择上传附件提供其他设计师下载</span>
	</div>
	<?php echo \F::form()->inputHidden('file')?>
	<div id="upload-file-container" class="upload-file-container">
		<a class="inputxt-file" id="upload-file-link" href="javascript:;">点击上传附件（支持zip,rar压缩包）</a>
		<span class="file-name">
		<?php if(\F::form()->getData('file')){?>
			<?php echo $file['description']?>
			<i class="icon-right" title="点此删除附件"></i>
		<?php }?>
		</span>
		<div class="progress-bar"><span class="progress-bar-percent"></span></div>
	</div>
</fieldset>