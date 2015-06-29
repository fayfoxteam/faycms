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
		<div id="thumbnail-container" style="margin-bottom:10px;"><a href="javascript:;" id="upload-thumbnail" class="btn">设置缩略图</a></div>
		<div id="thumbnail-preview-container">
		<?php 
			echo F::form()->inputHidden('thumbnail', array('id'=>'thumbnail-id'));
			if(!empty($page['thumbnail'])){
				echo Html::link(Html::img($page['thumbnail'], File::PIC_RESIZE, array(
					'dw'=>257,
				)), File::model()->getUrl($page['thumbnail']), array(
					'encode'=>false,
					'class'=>'fancybox-image',
					'title'=>false,
				));
				echo Html::link('移除缩略图', 'javascript:;', array(
					'id'=>'remove-thumbnail'
				));
			}
		?>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/browserplus-min.js')?>"></script>
<script>
$(function(){
	var uploader = new plupload.Uploader({
		runtimes : 'gears,html5,html4,flash,silverlight,browserplus',
		browse_button : 'upload-thumbnail',
		container : 'thumbnail-container',
		max_file_size : '2mb',
		url : system.url("admin/file/upload",{'t':'pages'}),
		flash_swf_url : system.url()+'flash/plupload.flash.swf',
		silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,gif,png,jpeg"}
		]
	});

	uploader.init();
	uploader.bind('FilesAdded', function(up, files) {
		$("#thumbnail-preview-container").html('<img src="'+system.url('images/loading.gif')+'" />');
		uploader.start();
	});
	
	uploader.bind('Error', function(up, error) {
		if(error.code == -600){
			alert("文件大小不能超过"+(parseInt(uploader.settings.max_file_size) / (1024 * 1024))+"M");
			return false;
		}else if(error.code == -601){
			alert('非法的文件类型');
			return false;
		}else{
			alert(error.message);
		}
	});

	uploader.bind('FileUploaded', function(up, file, response) {
		var resp = $.parseJSON(response.response);
		var html = [
			'<input type="hidden" name="thumbnail" value="', resp.id, '" />',
			'<a href="', resp.url, '" class="fancybox-image">',
				'<img src="', system.url('admin/file/pic', {
					'f':resp.id,
					't':4,
					'dw':257
				}), '" />',
			'</a>',
			'<a href="javascript:;" id="remove-thumbnail">移除缩略图</a>',
		].join('');
		$("#thumbnail-preview-container").html(html);
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$("#thumbnail-preview-container .fancybox-image").fancybox({
					'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic',
					'type' : 'image',
					'padding' : 0
				});
			});
		});
	});

	$(document).on('click', '#remove-thumbnail', function(){
		$('#thumbnail-preview-container').html('<input type="hidden" name="thumbnail" value="0" />');
	})
});
</script>