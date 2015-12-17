<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="box" id="box-thumbnail" data-name="thumbnail">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>缩略图</h4>
	</div>
	<div class="box-content">
		<div id="thumbnail-container" class="mb10">
			<a href="javascript:;" id="upload-thumbnail" class="btn">设置缩略图</a>
		</div>
		<div id="thumbnail-preview-container">
		<?php 
			echo F::form()->inputHidden('thumbnail', array('id'=>'thumbnail-id'));
			$thumbnail = F::form()->getData('thumbnail', 0);
			if(!empty($thumbnail)){
				echo Html::link(Html::img($thumbnail, File::PIC_RESIZE, array(
					'dw'=>257,
				)), File::getUrl($thumbnail), array(
					'encode'=>false,
					'class'=>'fancybox-image',
					'title'=>'点击查看原图',
				));
				echo Html::link('移除缩略图', 'javascript:;', array(
					'id'=>'remove-thumbnail'
				));
			}
		?>
		</div>
	</div>
</div>