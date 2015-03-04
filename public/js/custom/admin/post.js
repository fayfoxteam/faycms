var post = {
	'boxes':[],
	'thumbnail':function(){
		//设置缩略图
		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,gears,silverlight',
			browse_button : 'upload_thumbnail',
			container : 'thumbnail-container',
			max_file_size : '2mb',
			url : system.url("admin/file/upload",{'t':'posts'}),
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
		
		uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			$("#thumbnail-preview-container").html([
				'<input type="hidden" name="thumbnail" value="', resp.id, '" />',
				'<a href="', resp.url, '" class="fancybox-image">',
					'<img src="', system.url('admin/file/pic', {
						'f':resp.id,
						't':4,
						'dw':257
					}), '" />',
				'</a>',
				'<a href="javascript:;" id="remove-thumbnail">移除缩略图</a>',
			].join(''));
			system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$("#thumbnail-preview-container .fancybox-image").fancybox({
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
	'files':function(){
		//文件上传
		var files_uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,gears,silverlight',
			browse_button : 'upload-file-link',
			container: 'upload-file-container',
			max_file_size : '20mb',
			url : system.url("admin/file/upload",{'t':'posts'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap'
		});

		files_uploader.init();

		files_uploader.bind('FilesAdded', function(up, files) {
			files_uploader.start();
			$.each(files, function(i, data){
				$(".file-list").append([
					'<div class="dragsort-item" id="file-', data.id, '">',
						'<a class="dragsort-item-selector"></a>',
						'<a class="dragsort-rm" href="javascript:;"></a>',
						'<div class="dragsort-item-container">',
							'<span class="file-thumb">',
								'<img src="', system.url('images/loading.gif'), '" />',
							'</span>',
							'<div class="file-desc-container">',
								'<textarea class="file-desc autosize">', data.name, '</textarea>',
							'</div>',
							'<div class="clear"></div>',
							'<div class="progress-bar">',
								'<span class="progress-bar-percent"></span>',
							'</div>',
						'</div>',
					'</div>'
				].join(''));
			});
		});

		files_uploader.bind('UploadProgress', function(up, file) {
			$("#file-"+file.id+" .progress-bar-percent").animate({'width':file.percent+'%'});
		});

		files_uploader.bind('FileUploaded', function(up, file, response) {
			var resp = $.parseJSON(response.response);
			$file = $("#file-"+file.id);
			if('raw_name' in resp){
				$file.find('.file-desc').attr("name", 'description['+resp.id+']').autosize();
				$file.append('<input type="hidden" name="files[]" value="'+resp.id+'" />');
				$file.prepend('<a class="file-rm" href="javascript:;"></a>');
				
				
				if(resp.is_image){
					//是图片，用fancybox弹窗
					$file.find(".file-thumb").html([
						'<a href="', resp.url, '" class="file-thumb-link">',
							'<img src="'+resp.thumbnail+'" />',
						'</a>'
					].join(''));
					system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
						system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
							$(".file-thumb-link").fancybox({
								'transitionIn'	: 'elastic',
								'transitionOut'	: 'elastic',
								'type' : 'image',
								'padding' : 0
							});
						});
					});
				}else{
					//非图片，直接新窗口打开
					$file.find(".file-thumb").html([
						'<a href="', resp.url, '" target="_blank">',
							'<img src="'+resp.thumbnail+'" />',
						'</a>'
					].join(''));
				}
			}else{
				//非json数据，上传出错
				$file.remove();
				alert(resp);
			}
		});

		files_uploader.bind('Error', function(up, error) {
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
	'events':function(){
		$(document).on('change', '[name="cat_id"]', function(){
			//更新附加属性
			if($('#box-props').length){
				$("#box-props").block();
				$.ajax({
					'type': 'GET',
					'url': system.url('admin/post/get-prop-box', {
						'cat_id':$(this).val(),
						'post_id':post.post_id ? post.post_id : 0
					}),
					'cache': false,
					'success': function(resp){
						$("#box-props").unblock();
						$("#box-props").replaceWith(resp);
					}
				});
			}
			//更新附加分类
			if($('#box-category').length){
				$('#box-category').find('input').attr('disabled', false);
				$('#box-category').find('[name="post_category[]"][value="'+$(this).val()+'"]').attr({
					'checked':'checked',
					'disabled':'disabed'
				});
			}
		}).on('click', '#remove-thumbnail', function(){
			$('#thumbnail-preview-container').html('<input type="hidden" name="thumbnail" value="0" />');
		});
		
		common.beforeDragsortListItemRemove = function(obj){
			//拖拽列表若有报错，该项内部所有表单元素报错信息将被删除
			obj.find('input,select,textarea').poshytip('destroy');
		}
		common.afterDragsortListItemRemove = function(obj){
			//拖拽列表若有报错，该列表内所有表单元素将重新定位
			obj.find('input,select,textarea').each(function(){
				$(this).poshytip('hide').poshytip('show');
			});
		}
	},
	'autosize':function(){
		if(!$('textarea.autosize').length && system.inArray('files', post.boxes)){
			//页面没有autosize输入框，且附件box存在，则引入autosize输入框插件
			system.getScript(system.url('js/jquery.autosize.min.js'));
		}
	},
	'init':function(){
		if(system.inArray('thumbnail', post.boxes)){
			this.thumbnail();
		}
		if(system.inArray('files', post.boxes)){
			this.files();
		}
		this.events();
		this.autosize();
	}
};