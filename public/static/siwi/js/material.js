var material = {
	'tag':function(){
		//tags输入
		system.getScript(system.url('faycms/js/fayfox.textext.js'), function(){
			$("[name='tags']").ftextext({
				'url':system.url('tag/search.html')
			});
		});
	},
	'cats':function(){
		//分类选择
		//下拉框美化
		system.getScript(system.url('faycms/js/fayfox.select.js'), function(){
			$('[name="parent_cat"]').fselect({
				'afterSetChoice':function(o){
					if(common.validObj){
						common.validObj.check(false, '[name="cat_id"]');
					}
					var parent_cat = $(o).val();
					if(parent_cat){
						var parent_cat_title = '';
						$(o).find('option').each(function(){
							if($(this).val() == parent_cat){
								parent_cat_title = $(this).text();
							}
						});
						$(".thumbnail-container .cat-1").text(parent_cat_title);
					}
				}
			});
			$('[name="cat_id"]').fselect({
				'afterSetChoice':function(o){
					if(common.validObj){
						common.validObj.check(false, '[name="cat_id"]');
					}
					var cat_id = $(o).val();
					if(cat_id){
						var cat_id_title = '';
						$(o).find('option').each(function(){
							if($(this).val() == cat_id){
								cat_id_title = $(this).text();
							}
						});
						$(".thumbnail-container .cat-2").text(cat_id_title);
					}
				}
			});

			$("[name='parent_cat']").on('change', function(){
				$.ajax({
					'type': 'GET',
					'url': system.url('cat/get.html'),
					'data': {'pid':$(this).val()},
					'dataType': 'json',
					'cache': false,
					'success': function(resp){
						if(resp.status){
							var node = $("[name='cat_id']");
							node.html('');
							$.each(resp.data, function(i, n){
								if(material.cat_id && material.cat_id == n.id){
									node.append('<option value="'+n.id+'" seleced="selected">'+n.title+'</option>');
								}else{
									node.append('<option value="'+n.id+'">'+n.title+'</option>');
								}
							});
							node.fselect('update');
						}
					}
				});
			});
		});
		
		if(material.cat_id){
			$("[name='parent_cat']").change();
		}
	},
	'uploadFiles':function(){
		//附件上传
		//设置缩略图
		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,silverlight,gears',
			browse_button : 'upload-file-link',
			container : 'upload-file-container',
			max_file_size : '2mb',
			url : system.url("user/file/upload",{'t':'posts'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"},
				{title : "Zip files", extensions : "zip"}
			]
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files) {
			uploader.start();
			//初始化进度条
			$('#upload-file-container .file-name').text('');
			$("#upload-file-container .progress-bar-percent").css({'width':0});
		});
		
		uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			$('[name="file"]').val(resp.id);
			$('#upload-file-container .file-name').text(resp.client_name);
			$('#upload-file-container .file-name').append('<i class="icon-right" title="点此删除附件"></i>');
		});

		uploader.bind('UploadProgress', function(up, file) {
			$("#upload-file-container .progress-bar-percent").animate({'width':file.percent+'%'});
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
	},
	'removeFile':function(){
		$('#upload-file-container').on('click', '.icon-right', function(){
			$('[name="file"]').val('');
			$(this).parent().parent().find('.progress-bar-percent').css({'width':0});
			$(this).parent().html('');
		});
	},
	'uploadThumbnail':function(){
		//设置缩略图
		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,silverlight,gears',
			browse_button : 'upload-thumbnail-link',
			container : 'upload-thumbnail-container',
			max_file_size : '2mb',
			url : system.url("user/file/upload",{'t':'posts'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files) {
			$('.thumbnail-container img').attr('src', system.url('images/loading.gif')).addClass('loading');
			uploader.start();
		});
		
		uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);

			$('.thumbnail-container img').attr('src', system.url('file/pic', {
				'f':resp.id,
				't':4,
				'dw':283,
				'dh':217
			})).removeClass('loading');
			$('[name="thumbnail"]').val(resp.id);
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
	},
	'uploadPreview':function(){
		//设置缩略图
		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,silverlight,gears',
			browse_button : 'upload-preview-link',
			container : 'upload-preview-container',
			max_file_size : '2mb',
			url : system.url("user/file/upload",{'t':'posts'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files) {
			$("#upload-preview-container .progress-bar-percent").css({'width':0});
			uploader.start();
		});
		
		uploader.bind('UploadProgress', function(up, file) {
			$("#upload-preview-container .progress-bar-percent").animate({'width':file.percent+'%'});
		});
		
		uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			
			$("#upload-preview-link").hide();
			$('[name="content"]').val('<img src="'+resp.url+'" />');
			$("#upload-preview-container").append('<img src="'+resp.url+'" />');
			$(".preview-container").addClass('uploaded');
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
	},
	'removePreview':function(){
		$(".preview-container").on('click', '.remove-link', function(){
			$("#upload-preview-link").show();
			$('[name="content"]').val('');
			$("#upload-preview-container img").remove();
			$(".preview-container").removeClass('uploaded');
			$("#upload-preview-container .progress-bar-percent").css({'width':0});
		});
	},
	'events':function(){
		//算个预览功能
		$('[name="title"]').on('change', function(){
			$(".thumbnail-container .title").text($(this).val());
		});
	},
	'init':function(){
		this.tag();
		this.cats();
		this.uploadFiles();
		this.removeFile();
		this.uploadThumbnail();
		this.uploadPreview();
		this.removePreview();
		this.events();
	}
};