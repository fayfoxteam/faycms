<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\tables\Roles;
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
<?php if(isset($config['files'])){?>
<?php foreach($config['files'] as $d){?>
	<div class="dragsort-item">
		<?php echo Html::inputHidden('photos[]', $d['file_id'])?>
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
<div class="box <?php if(!in_array(Roles::ITEM_SUPER_ADMIN, F::session()->get('roles')))echo 'closed';?>">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>渲染模版</h4>
	</div>
	<div class="box-content">
		<?php echo F::form('widget')->textarea('template', array(
			'class'=>'form-control h90 autosize',
		))?>
		<p class="fc-grey mt5">
			若模版内容符合正则<code>/^[\w_-]+\/[\w_-]+\/[\w_-]+$/</code>，
			即类似<code>frontend/widget/template</code><br />
			则会调用当前application下符合该相对路径的view文件。<br />
			否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
		</p>
	</div>
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
			runtimes : 'html5,html4,flash,gears,silverlight',
			browse_button : 'plupload_browse_button',
			container: 'drag_drop_area',
			drop_element: 'drag_drop_area',
			max_file_size : '2mb',
			url : system.url('admin/file/upload', {'cat':'widget'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});
		
		jq_camera.uploadObj.init();
		
		jq_camera.uploadObj.bind('FilesAdded', function(up, files) {
			jq_camera.uploadObj.start();
			$.each(files, function(i, data){
				$('.file-list').append(['<div class="dragsort-item" id="file-', data.id, '">',
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
			$('#file-'+file.id+' .progress-bar-percent').animate({'width':file.percent+'%'});
		});
		
		jq_camera.uploadObj.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			$file = $('#file-'+file.id);
			$file.find('.photo-title').attr('name', 'titles['+resp.data.id+']');
			$file.find('.photo-link').attr('name', 'links['+resp.data.id+']');
			$file.append('<input type="hidden" name="photos[]" value="'+resp.data.id+'" />');
			$file.prepend('<a class="photo-rm" href="javascript:;" data-id="'+resp.data.id+'"></a>');
			
			//是图片，用fancybox弹窗
			$file.find('.file-thumb').html([
				'<a href="', resp.data.url, '" title="'+resp.data.client_name+'" class="photo-thumb-link">',
					'<img src="'+resp.data.thumbnail+'" />',
				'</a>'
			].join(''));
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$('.photo-thumb-link').fancybox({
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
				alert('文件大小不能超过'+(parseInt(files_uploader.settings.max_file_size) / (1024 * 1024))+'M');
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