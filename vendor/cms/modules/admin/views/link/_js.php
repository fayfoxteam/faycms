<script type="text/javascript" src="<?php echo $this->assets('js/plupload.full.js')?>"></script>
<script>
var link = {
	'uploadObj':null,
	'uploadLogo':function(){
		link.uploadObj = new plupload.Uploader({
			runtimes : 'gears,html5,html4,flash,silverlight,browserplus',
			browse_button : 'upload-logo-link',
			container : 'upload-logo-container',
			max_file_size : '2mb',
			url : system.url('admin/file/upload', {'cat':'link'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});

		link.uploadObj.init();
		link.uploadObj.bind('FilesAdded', function(up, files) {
			$('#upload-logo-preview').html('<img src="'+system.assets('images/loading.gif')+'" />');
			link.uploadObj.start();
		});
		
		link.uploadObj.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			var html = [
				'<input type="hidden" name="logo" value="', resp.id, '" />',
				'<a href="', resp.url, '" class="fancybox-image">',
					'<img src="', resp.url, '" />',
				'</a>'
			].join('');
			$('#upload-logo-preview').html(html);
		});

		link.uploadObj.bind('Error', function(up, error) {
			if(error.code == -600){
				alert('文件大小不能超过'+(parseInt(link.uploadObj.settings.max_file_size) / (1024 * 1024))+'M');
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
		this.uploadLogo();
	}
};
$(function(){
	link.init();
});
</script>