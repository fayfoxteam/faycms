var user = {
	'user_id':0,
	'avatar':function(){
		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,gears,silverlight,browserplus',
			browse_button : 'upload-avatar',
			container : 'avatar-container',
			max_file_size : '2mb',
			url : system.url('admin/file/upload', {'cat':'avatar'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files) {
			$('#avatar-img, #avatar-img-circle').attr('src', system.assets('images/loading.gif'));
			uploader.start();
		});

		uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);

			$('#avatar-id').val(resp.id);
			$('#avatar-img').attr('src', system.url('admin/file/pic', {
				'f':resp.id,
				't':4,
				'dw':178,
				'dh':178
			}))
			.parent().attr('href', system.url('admin/file/pic', {
				'f':resp.id
			}));
			$('#avatar-img-circle').attr('src', system.url('admin/file/pic', {
				'f':resp.id,
				't':2
			}))
			.parent().attr('href', system.url('admin/file/pic', {
				'f':resp.id
			}));
			
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
	},
	'prop':function(){
		$(document).on('change', '[name="roles[]"]', function(){
			$('#prop-panel').block();
			var value = [];
			$('.user-roles:checked').each(function(){ 
				value.push($(this).val()); 
			});
			$.ajax({
				'type': 'GET',
				'url': system.url('admin/user/get-prop-panel'),
				'data': {
					'role_ids[]':value,
					'user_id':user.user_id ? user.user_id : 0
				},
				'cache': false,
				'success': function(resp){
					$('#prop-panel').unblock();
					$('#prop-panel').find('input,select,textarea').each(function(){
						$(this).poshytip('hide');
					});
					$('#prop-panel').html(resp);
				}
			});
		});
	},
	'init':function(){
		this.avatar();
		this.prop();
	}
};