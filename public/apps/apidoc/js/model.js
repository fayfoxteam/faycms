/**
 * 数据模型管理
 */
var model = {
	'propForm': null,
	/**
	 * 添加属性
	 */
	'addProp': function(){
		common.loadFancybox(function(){
			$('#add-prop-link').fancybox({
				'onComplete':function(){
					//初始化编辑框
					var $addPropForm = $('#add-prop-form');
					$addPropForm.find('[name="name"]').val('');
					$addPropForm.find('[name="required"][value="0"]').attr('checked', 'checked');
					$addPropForm.find('[name="description"]').val('');
					$addPropForm.find('[name="sample"]').val('');
					$addPropForm.find('[name="since"]').val('');
				}
			});
		});
	},
	/**
	 * 编辑属性
	 */
	'editProp': function(){
        common.loadFancybox(function(){
			$('.edit-prop-link').fancybox({
				'onComplete':function(instance, slide){
					var $container = slide.opts.$orig.parent().parent().parent().parent();
					var $editPropForm = $('#edit-prop-form');
					//初始化编辑框
					$('#editing-prop-name').text($container.find('.input-name').val());
					$editPropForm.find('[name="selector"]').val($container.attr('id'));
					$editPropForm.find('[name="name"]').val($container.find('.input-name').val());
					$editPropForm.find('[name="type_name"]').val($container.find('.input-type-name').val());
					$editPropForm.find('[name="is_array"][value="'+$container.find('.input-is-array').val()+'"]').attr('checked', 'checked');
					$editPropForm.find('[name="description"]').val($container.find('.input-description').val());
					$editPropForm.find('[name="sample"]').val($container.find('.input-sample').val());
					$editPropForm.find('[name="since"]').val($container.find('.input-since').val());
				}
			});
		});
	},
	/**
	 * 模型选择自动补全
	 */
	'autocomplete': function(){
		system.getScript(system.assets('faycms/js/fayfox.autocomplete.js'), function(){
			$("#add-prop-type-name").autocomplete({
				"url" : system.url('admin/model/search'),
				'startSuggestLength': 0,
				'onSelect': function(obj, data){
					obj.val(data.name);
					model.propForm.check(obj);
				},
				'zindex': '111150'
			});
			$("#edit-prop-type-name").autocomplete({
				"url" : system.url('admin/model/search'),
				'startSuggestLength': 0,
				'onSelect': function(obj, data){
					obj.val(data.name);
					model.propForm.check(obj);
				},
				'zindex': '111150'
			});
		});
	},
	/**
	 * 验证输入参数表单
	 * 这个表单并不会被提交，只是做一下表单验证
	 */
	'validProp': function(rules, labels){
		system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
			model.propForm = $('.prop-form').validform({
				'onError': function(obj, msg, rule){
					var last = $.validform.getElementsByName(obj).last();
					last.poshytip('destroy');
					//报错
					last.poshytip({
						'className': 'tip-twitter',
						'showOn': 'none',
						'alignTo': 'target',
						'alignX': 'inner-right',
						'offsetX': -60,
						'offsetY': 5,
						'content': msg
					}).poshytip('show');
					$('.dialog').unblock();
				},
				'onSuccess': function(obj){
					var last = $.validform.getElementsByName(obj).last();
					last.poshytip('destroy');
				},
				'beforeCheck': function(form){
					if(form.attr('id').indexOf('add') == 0){
						$('#add-prop-dialog').block({
							'zindex': 120000
						});
					}else{
						$('#edit-prop-dialog').block({
							'zindex': 120000
						});
					}
				},
				'beforeSubmit': function(form){
					//获取输入值
					var name = form.find('[name="name"]').val();
					var type_name = form.find('[name="type_name"]').val();
					var is_array = form.find('[name="is_array"]:checked').val();
					var description = form.find('[name="description"]').val();
					var sample = form.find('[name="sample"]').val();
					var since = form.find('[name="since"]').val();
					
					if(form.attr('id').indexOf('add') == 0){
						//添加
						var timestamp = new Date().getTime();
						
						//插入行
						$('#model-list').append(['<div class="dragsort-item" id="model-', timestamp, '">',
							'<input type="hidden" name="props[', timestamp, '][name]" value="', name, '" class="input-name" />',
							'<input type="hidden" name="props[', timestamp, '][type_name]" value="', type_name, '" class="input-type-name" />',
							'<input type="hidden" name="props[', timestamp, '][is_array]" value="', is_array, '" class="input-is-array" />',
							'<input type="hidden" name="props[', timestamp, '][description]" value="', description, '" class="input-description" />',
							'<input type="hidden" name="props[', timestamp, '][sample]" value="', sample, '" class="input-sample" />',
							'<input type="hidden" name="props[', timestamp, '][since]" value="', since, '" class="input-since" />',
							'<a class="dragsort-rm" href="javascript:;"></a>',
							'<a class="dragsort-item-selector"></a>',
							'<div class="dragsort-item-container">',
								'<span class="ib wp25">',
									'<strong>', name, '</strong>',
									'<p>',
										'<a href="#edit-prop-dialog" class="edit-prop-link">编辑</a>',
									'</p>',
								'</span>',
								'<span class="ib wp15 vat">', type_name, (is_array == '1' ? ' []' : ''), '</span>',
								'<span class="ib vat">', description, '</span>',
							'</div>',
						'</div>'].join(''));
						model.editProp();
					}else{
						//编辑
						var selector = form.find('[name="selector"]').val();
						$prop = $('#'+selector);
						
						//编辑隐藏域
						$prop.find('.input-name').val(name);
						$prop.find('.input-type-name').val(type_name);
						$prop.find('.input-is-array').val(is_array);
						$prop.find('.input-description').val(description);
						$prop.find('.input-sample').val(sample);
						$prop.find('.input-since').val(since);
						
						//修改行显示
						$prop.find('span:eq(0) strong').text(name);
						$prop.find('span:eq(1)').text(type_name + (is_array == '1' ? ' []' : ''));
						$prop.find('span:eq(2)').text(description);
					}
					
					$('.dialog').unblock();
					$.fancybox.close();
					return false;
				}
			}, rules, labels);
		});
	},
	'init': function(){
		this.addProp();
		this.editProp();
		this.autocomplete();
	}
}