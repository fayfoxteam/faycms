var post = {
	'boxes':[],
	'roleCats':null,
	'thumbnail':function(){
		//设置缩略图
		system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
			uploader.thumbnail({
				'cat': 'post',
			});
		});
	},
	'files':function(){
		//附件
		system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
			uploader.files({
				'cat': 'post',
			});
		});
	},
	'events':function(){
		$(document).on('change', '[name="cat_id"]', function(){
			//更新附加属性
			if($('#box-props').length){
				$('#box-props').block();
				$.ajax({
					'type': 'GET',
					'url': system.url('admin/post/get-prop-box', {
						'cat_id':$(this).val(),
						'post_id':post.post_id ? post.post_id : 0
					}),
					'cache': false,
					'success': function(resp){
						$('#box-props').unblock();
						$('#box-props').replaceWith(resp);
					}
				});
			}
			//更新附加分类
			if($('#box-category').length){
				$('#box-category').find('input').each(function(){
					//标有data-disabled的为权限不允许编辑的分类
					if(!$(this).attr('data-disabled')){
						$(this).attr('disabled', false);
					}
				});
				$('#box-category').find('[name="post_category[]"][value="'+$(this).val()+'"]').attr({
					'checked':'checked',
					'disabled':'disabed'
				});
			}
		});
		$('#box-operation').on('click', '#edit-status-link', function(){
			$('#edit-status-container').show();
		}).on('click', '#set-status-editing', function(){
			var status = $('#edit-status-selector').val();
			var status_text = $('#edit-status-selector').find('[value="'+status+'"]').text();
			$('input[name="status"]').val(status);
			$('#crt-status').text(status_text);
			$('#edit-status-container').hide();
		}).on('click', '#cancel-status-editing', function(){
			$('#edit-status-container').hide();
		});
	},
	'autosize':function(){
		if(!$('textarea.autosize').length && system.inArray('files', post.boxes)){
			//页面没有autosize输入框，且附件box存在，则引入autosize输入框插件
			system.getScript(system.assets('js/jquery.autosize.min.js'));
		}
	},
	'setRoleCats':function(){
		if(system.inArray('main-category', post.boxes)){
			$('#box-main-category [name="cat_id"] option').each(function(){
				if(!system.inArray($(this).val(), post.roleCats)){
					$(this).attr('disabled', 'disabled');
				}
			});
		}
		if(system.inArray('category', post.boxes)){
			$('#box-category [name="post_category[]"]').each(function(){
				if(!system.inArray($(this).val(), post.roleCats)){
					$(this).attr('disabled', 'disabled');
					$(this).attr('data-disabled', 'data-disabled');
				}
			});
		}
	},
	'init':function(){
		if(system.inArray('thumbnail', post.boxes)){
			this.thumbnail();
		}
		if(system.inArray('files', post.boxes)){
			this.files();
		}
		if(system.inArray('tags', post.boxes)){
			system.getScript(system.assets('faycms/js/fayfox.textext.js'), function(){
				$('#tags').ftextext({
					'url':system.url('admin/tag/search'),
					'width':'100%'
				});
			});
		}
		if(this.roleCats !== null){
			this.setRoleCats();
		}
		this.events();
		this.autosize();
	}
};