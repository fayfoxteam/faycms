/**
 * api管理
 */
var api = {
	'typeMap': {},
	/**
	 * 添加请求参数
	 */
	'addInputParameter': function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('#add-input-parameter-link').fancybox({
					'padding': 0,
					'centerOnScroll': true,
					'type' : 'inline',
					'titleShow':false,
					'onClosed': function(o){
						$($(o).attr('href')).find('input,select,textarea').each(function(){
							$(this).poshytip('hide');
						});
					},
					'onStart':function(o){
						//初始化编辑框
						$('#add-input-parameter-form').find('[name="name"]').val('');
						$('#add-input-parameter-form').find('[name="required"][value="0"]').attr('checked', 'checked');
						$('#add-input-parameter-form').find('[name="description"]').val('');
						$('#add-input-parameter-form').find('[name="sample"]').val('');
						$('#add-input-parameter-form').find('[name="since"]').val('');
					}
				});
			});
		});
	},
	/**
	 * 编辑请求参数
	 */
	'editInputParameter': function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('.edit-input-parameter-link').fancybox({
					'padding': 0,
					'centerOnScroll': true,
					'type' : 'inline',
					'titleShow':false,
					'onClosed': function(o){
						$($(o).attr('href')).find('input,select,textarea').each(function(){
							$(this).poshytip('hide');
						});
					},
					'onStart':function(o){
						var $container = $(o).parent().parent();
						//初始化编辑框
						$('#editing-input-parameter-name').text($container.find('.input-name').val());
						$('#edit-input-parameter-form').find('[name="selector"]').val($container.parent().attr('id'));
						$('#edit-input-parameter-form').find('[name="name"]').val($container.find('.input-name').val());
						$('#edit-input-parameter-form').find('[name="type"]').val($container.find('.input-type').val());
						$('#edit-input-parameter-form').find('[name="required"][value="'+$container.find('.input-required').val()+'"]').attr('checked', 'checked');
						$('#edit-input-parameter-form').find('[name="description"]').val($container.find('.input-description').val());
						$('#edit-input-parameter-form').find('[name="sample"]').val($container.find('.input-sample').val());
						$('#edit-input-parameter-form').find('[name="since"]').val($container.find('.input-since').val());
					}
				});
			});
		});
	},
	/**
	 * 验证输入参数表单
	 * 这个表单并不会被提交，只是做一下表单验证
	 */
	'validInputParameter': function(rules, labels){
		system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
			$('.input-parameter-form').validform({
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
				},
				'onSuccess': function(obj){
					var last = $.validform.getElementsByName(obj).last();
					last.poshytip('destroy');
				},
				'beforeSubmit': function(form){
					if(form.attr('id').indexOf('add') == 0){
						//添加
						var timestamp = new Date().getTime();
						//获取输入值
						var name = form.find('[name="name"]').val();
						var type = form.find('[name="type"]').val();
						var required = form.find('[name="required"]:checked').val();
						var description = form.find('[name="description"]').val();
						var sample = form.find('[name="sample"]').val();
						var since = form.find('[name="since"]').val();
						
						//插入表格行
						$('#input-parameter-table tbody').append(['<tr id="new-', timestamp, '" valign="top">',
							'<td>',
								'<input type="hidden" name="inputs[', timestamp, '][name]" value="', name, '" class="input-name" />',
								'<input type="hidden" name="inputs[', timestamp, '][type]" value="', type, '" class="input-type" />',
								'<input type="hidden" name="inputs[', timestamp, '][required]" value="', required, '" class="input-required" />',
								'<input type="hidden" name="inputs[', timestamp, '][description]" value="', description, '" class="input-description" />',
								'<input type="hidden" name="inputs[', timestamp, '][sample]" value="', sample, '" class="input-sample" />',
								'<input type="hidden" name="inputs[', timestamp, '][since]" value="', since, '" class="input-since" />',
								'<strong>', system.encode(name), '</strong>',
								'<div class="row-actions">',
									'<a href="#edit-input-parameter-dialog" class="edit-input-parameter-link">编辑</a>',
									'<a href="javascript:;" class="fc-red remove-input-parameter-link">删除</a>',
								'</div>',
							'</td>',
							'<td>', api.typeMap[type], '</td>',
							'<td>', (required == 1 ? '<span class="fc-green">是</span>' : '否'), '</td>',
							'<td>', system.encode(since), '</td>',
							'<td>', system.encode(description), '</td>',
						'</tr>'].join(''));
						api.editInputParameter();
						$('#input-parameter-table tbody tr').removeClass('alternate');
						$('#input-parameter-table tbody tr:even').addClass('alternate');
					}else{
						//编辑
						var selector = form.find('[name="selector"]').val();
						$input = $('#'+selector);
						//获取输入值
						var name = form.find('[name="name"]').val();
						var type = form.find('[name="type"]').val();
						var required = form.find('[name="required"]:checked').val();
						console.log(required);
						var description = form.find('[name="description"]').val();
						var sample = form.find('[name="sample"]').val();
						var since = form.find('[name="since"]').val();
						
						//编辑隐藏域
						$input.find('.input-name').val(name);
						$input.find('.input-type').val(type);
						$input.find('.input-required').val(required);
						$input.find('.input-description').val(description);
						$input.find('.input-sample').val(sample);
						$input.find('.input-since').val(since);
						
						//修改表格行显示
						$input.find('td:eq(0) strong').text(name);
						$input.find('td:eq(1)').text(api.typeMap[type]);
						$input.find('td:eq(2)').html(required == 1 ? '<span class="fc-green">是</span>' : '否');
						$input.find('td:eq(3)').text(since);
						$input.find('td:eq(4)').text(description);
					}
					
					$.fancybox.close();
					return false;
				},
			}, rules, labels);
		});
	},
	/**
	 * 移除请求参数
	 */
	'removeInputParameter': function(){
		$('#input-parameter-table').on('click', '.remove-input-parameter-link', function(){
			if(confirm('确定要删除此请求参数吗？')){
				$(this).parent().parent().parent().fadeOut(function(){
					$(this).remove();
				})
			}
		})
	},
	'init': function(){
		this.addInputParameter();
		this.editInputParameter();
		this.removeInputParameter();
	}
}