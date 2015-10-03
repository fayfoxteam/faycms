<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="drag-drop-area" id="drag-drop-area">
	<div class="drag-drop-inside">
		<p class="drag-drop-info">将文件拖拽至此</p>
		<p>或</p>
		<p class="drag-drop-buttons">
			<a class="plupload-browse-button btn btn-grey" id="plupload-browse-button">选择文件</a>
		</p>
	</div>
</div>
<div class="dragsort-list file-list">
<?php if(!empty($config['files'])){?>
<?php foreach($config['files'] as $d){?>
	<div class="dragsort-item">
		<?php echo Html::inputHidden('files[]', $d['file_id'])?>
		<a class="dragsort-rm" href="javascript:;"></a>
		<a class="dragsort-item-selector"></a>
		<div class="dragsort-item-container">
			<span class="file-thumb">
			<?php 
				echo Html::link(Html::img($d['file_id'], 2), File::getUrl($d['file_id']), array(
					'class'=>'photo-thumb-link',
					'encode'=>false,
					'title'=>Html::encode($d['title']),
				));
			?>
			</span>
			<div class="file-desc-container">
				<?php echo Html::inputText("titles[{$d['file_id']}]", $d['title'], array(
					'class'=>'photo-title mb5 form-control',
					'placeholder'=>'标题',
				))?>
				<?php echo Html::inputText("links[{$d['file_id']}]", $d['link'], array(
					'class'=>'photo-link mb5 form-control',
					'placeholder'=>'链接地址',
				))?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php }?>
<?php }?>
</div>
<script type="text/javascript">
var widget_slides = {
	'uploadObj':null,
	'preview':function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".photo-thumb-link").fancybox({
					'transitionIn' : 'elastic',
					'transitionOut' : 'elastic',
					'type' : 'image',
					'padding' : 0
				});
			});
		});
	},
	'files':function(){
		system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
			uploader.files({
				'browse_button': 'plupload-browse-button',
				'container': 'drag-drop-area',
				'drop_element': 'drag-drop-area',
				'cat': 'widget',
				'image_only': true,
				'file_info': ['title', 'link']
			});
		});
	},
	'init':function(){
		this.preview();
		this.files();
	}
};
$(function(){
	widget_slides.init();
	
});
</script>