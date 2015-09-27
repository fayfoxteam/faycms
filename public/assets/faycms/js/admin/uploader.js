/**
 * 将一些上传场景抽象出来。例如：缩略图，附件
 * 可选参数：
 * options.browse_button: 上传按钮id。默认为upload-thumbnail
 * options.container: 上传控件外层div id。默认为thumbnail-container'
 * options.max_file_size: 文件大小限制。默认为2
 * options.cat: 上传文件所属分类。默认为other
 * options.image_only: 若为true，则仅允许上传图片。默认为false
 */
var uploader = {
	'thumbnail':function(options){
		options = options || {};
		var uploader;
		system.getScript(system.assets('js/plupload.full.js'), function(){
			//设置缩略图
			uploader = new plupload.Uploader({
				runtimes : 'html5,html4,flash,gears,silverlight',
				browse_button : options.browse_button ? options.browse_button : 'upload-thumbnail',
				container : options.container ? options.container : 'thumbnail-container',
				max_file_size : (options.max_file_size ? options.max_file_size : 2)+'mb',
				url : system.url('admin/file/img-upload', {'cat':'post'}),
				flash_swf_url : system.url()+'flash/plupload.flash.swf',
				silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
				filters : [
					{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
				]
			});
			
			uploader.init();
			uploader.bind('FilesAdded', function(up, files) {
				$('#thumbnail-preview-container').html('<img src="'+system.assets('images/loading.gif')+'" />');
				uploader.start();
			});
			
			uploader.bind('FileUploaded', function(up, file, response) {
				var resp = $.parseJSON(response.response);
				$('#thumbnail-preview-container').html([
					'<input type="hidden" name="thumbnail" value="', resp.data.id, '" />',
					'<a href="', resp.data.url, '" class="fancybox-image">',
						'<img src="', system.url('admin/file/pic', {
							'f':resp.data.id,
							't':4,
							'dw':257
						}), '" />',
					'</a>',
					'<a href="javascript:;" id="remove-thumbnail">移除缩略图</a>',
				].join(''));
				system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
					system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
						$('#thumbnail-preview-container .fancybox-image').fancybox({
							'transitionIn'	: 'elastic',
							'transitionOut'	: 'elastic',
							'type' : 'image',
							'padding' : 0
						});
					});
				});
			});
			
			uploader.bind('Error', function(up, error) {
				if(error.code == -600){
					alert('文件大小不能超过'+(parseInt(uploader.settings.max_file_size) / (1024 * 1024))+'M');
					return false;
				}else if(error.code == -601){
					alert('非法的文件类型');
					return false;
				}else{
					alert(error.message);
				}
			});
		});
		
		return uploader;
	}
};