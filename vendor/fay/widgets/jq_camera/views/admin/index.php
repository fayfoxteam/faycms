<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="drag_drop_area" id="drag_drop_area">
	<div class="drag_drop_inside">
		<p class="drag_drop_info">将文件拖拽至此</p>
		<p>或</p>
		<p class="drag_drop_buttons">
			<a class="plupload_browse_button btn btn-grey" id="plupload_browse_button">选择文件</a>
		</p>
	</div>
</div>
<div class="dragsort-list file-list">
<?php if(isset($data['files'])){?>
<?php foreach($data['files'] as $d){?>
	<div class="dragsort-item">
		<?php echo Html::inputHidden('photos[]', $d['file_id'])?>
		<a class="dragsort-rm" href="javascript:;"></a>
		<a class="dragsort-item-selector"></a>
		<div class="dragsort-item-container">
			<span class="file-thumb">
			<?php
				echo Html::link(Html::img($d['file_id'], 2), File::model()->getUrl($d['file_id']), array(
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
<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script type="text/javascript">
var jq_camera = {
	'uploadObj':null,
	'preview':function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".photo-thumb-link").fancybox({
					'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic',
					'type' : 'image',
					'padding' : 0
				});
			});
		});
	},
	'files':function(){
		//uploader
		jq_camera.uploadObj = new plupload.Uploader({
			runtimes : 'html5,html4,flash,gears,silverlight,browserplus',
			browse_button : 'plupload_browse_button',
			container: 'drag_drop_area',
			drop_element: "drag_drop_area",
			max_file_size : '2mb',
			url : system.url("admin/file/upload", {'t':'widget'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"}
			]
		});
		
		jq_camera.uploadObj.init();
		
		jq_camera.uploadObj.bind('FilesAdded', function(up, files) {
			jq_camera.uploadObj.start();
			$.each(files, function(i, data){
				$(".file-list").append(['<div class="dragsort-item" id="file-', data.id, '">',
					'<a class="dragsort-rm" href="javascript:;"></a>',
					'<a class="dragsort-item-selector"></a>',
					'<div class="dragsort-item-container">',
						'<span class="file-thumb">',
							'<img src="', system.assets('images/loading.gif'), '" />',
						'</span>',
						'<div class="file-desc-container">',
							'<input type="text" class="photo-title mb5 form-control" placeholder="标题" value="', data.name, '" />',
							'<input type="text" class="photo-link mb5 form-control" placeholder="链接地址" />',
						'</div>',
						'<div class="clear"></div>',
						'<div class="progress-bar">',
							'<span class="progress-bar-percent"></span>',
						'</div>',
					'</div>',
				'</div>'].join(''));
			});
		});
		
		jq_camera.uploadObj.bind('UploadProgress', function(up, file) {
			$("#file-"+file.id+" .progress-bar-percent").animate({'width':file.percent+'%'});
		});
		
		jq_camera.uploadObj.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			$file = $("#file-"+file.id);
			$file.find('.photo-title').attr("name", 'titles['+resp.id+']');
			$file.find('.photo-link').attr("name", 'links['+resp.id+']');
			$file.append('<input type="hidden" name="photos[]" value="'+resp.id+'" />');
			$file.prepend('<a class="photo-rm" href="javascript:;" data-id="'+resp.id+'"></a>');
			
			//是图片，用fancybox弹窗
			$file.find(".file-thumb").html([
				'<a href="', resp.url, '" title="'+resp.client_name+'" class="photo-thumb-link">',
					'<img src="'+resp.thumbnail+'" />',
				'</a>'
			].join(''));
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$(".photo-thumb-link").fancybox({
						'transitionIn'	: 'elastic',
						'transitionOut'	: 'elastic',
						'type' : 'image',
						'padding' : 0
					});
				});
			});
		});

		jq_camera.uploadObj.bind('Error', function(up, error) {
			if(error.code == -600){
				alert("文件大小不能超过"+(parseInt(files_uploader.settings.max_file_size) / (1024 * 1024))+"M");
				return false;
			}else if(error.code == -601){
				alert('非法的文件类型');
				return false;
			}else{
				alert(error.message);
			}
		});
	},
	'init':function(){
		this.preview();
		this.files();
	}
};
$(function(){
	jq_camera.init();
	
});
</script>