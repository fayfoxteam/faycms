<?php
use fay\helpers\Html;
use fay\services\File;
?>
<div class="box" id="box-files" data-name="files">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>图集</h4>
	</div>
	<div class="box-content">
		<p class="fc-grey">附件的用途视主题而定，一般用于画廊效果</p>
		<div id="upload-file-container" class="mt5">
			<?php echo Html::link('上传附件', 'javascript:;', array(
				'class'=>'btn',
				'id'=>'upload-file-link',
			))?>
		</div>
		<div class="dragsort-list file-list">
		<?php if(!empty($files)){?>
			<?php foreach($files as $p){?>
				<div class="dragsort-item">
					<?php echo Html::inputHidden('files[]', $p['file_id'])?>
					<a class="dragsort-rm" href="javascript:;"></a>
					<a class="dragsort-item-selector"></a>
					<div class="dragsort-item-container">
						<span class="file-thumb"><?php 
							$full_file_path = File::getUrl($p['file_id']);
							echo Html::link(Html::img($p['file_id'], File::PIC_THUMBNAIL), $full_file_path, array(
								'class'=>'file-thumb-link fancybox-image',
								'encode'=>false,
								'title'=>false,
							));
						?></span>
						<div class="file-desc-container">
							<?php echo Html::textarea("description[{$p['file_id']}]", $p['description'], array(
								'class'=>'form-control file-desc autosize',
								'placeholder'=>'照片描述',
							));?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			<?php }?>
		<?php }?>
		</div>
		<div class="clear"></div>
	</div>
</div>