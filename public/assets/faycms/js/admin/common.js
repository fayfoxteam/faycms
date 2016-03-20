var common = {
	'filebrowserImageUploadUrl':null,	//可视化编辑器的文件上传路径
	'filebrowserFlashUploadUrl':null,	//可视化编辑器的Flash上传路径
	'dragsortKey':null,	//用于自动保存dragsort排序
	'validformParams':{
		'forms':{},
		'settings':{
			'poshytip':{//利用poshytip插件报错
				'init': function(){
					system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
					system.getScript(system.assets('js/jquery.poshytip.min.js'));
				},
				'showAllErrors': true,
				'onAjaxEnd': function(obj, resp){
					if(!resp.status){
						$('body').unblock();
					}
				},
				'beforeCheck': function(){
					$('body').block({
						'zindex': 1300
					});
				},
				'onError': function(obj, msg, rule){
					$('body').unblock();
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
				}
			},
			'setting':{//管理员界面顶部设置表单
				'init': function(){
					system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
					system.getScript(system.assets('js/jquery.poshytip.min.js'));
				},
				'ajaxSubmit': true,
				'beforeSubmit': function(){
					$('#setting-form-submit').nextAll('span').remove();
					$('#setting-form-submit').after('<img src="'+system.assets('images/throbber.gif')+'" class="submit-loading" />');
				},
				'afterAjaxSubmit': function(resp){
					if(resp.status){
						$('#setting-form-submit').nextAll('img,span,a').remove();
						$('#setting-form-submit').after('<span class="fc-green" style="margin-left:6px;">保存成功，刷新页面后生效。</span><a href="javascript:window.location.reload();">点此刷新</a>');
					}else{
						common.alert(resp.message);
					}
				},
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
				}
			}
		}
	},
	'settingValidform':null,
	'fancybox': function(){
		//弹窗
		if($('.fancybox-image').length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$('.fancybox-image').fancybox({
						'transitionIn' : 'elastic',
						'transitionOut' : 'elastic',
						'type' : 'image',
						'padding': 0,
						'centerOnScroll': true
					});
				});
			});
		}
		if($('.fancybox-inline').length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$('.fancybox-inline').fancybox({
						'padding': 0,
						'centerOnScroll': true,
						'onClosed': function(o){
							$($(o).attr('href')).find('input,select,textarea').each(function(){
								$(this).poshytip('hide');
							});
						},
						'type' : 'inline'
					});
				});
			});
		}
		if($('.fancybox-iframe').length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$('.fancybox-iframe').fancybox({
						'centerOnScroll': true,
						'type' : 'iframe',
						'width' : 750,
						'autoDimensions' : true
					});
				});
			});
		}
		if($('.fancybox-close').length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$(document).on('click', '.fancybox-close', function(){
						$.fancybox.close();
					});
				});
			});
		}
		
	},
	'notification': function(){
		//将所有系统消息置为已读状态
		$(document).on('click', '.header-notification-mute', function(){
			$.ajax({
				type: 'GET',
				url: system.url('admin/notification/mute'),
				dataType: 'json',
				cache: false,
				success: function(data){
					common.headerNotification();
				}
			});
		});
		//跳转链接特殊处理
		$('#faycms-messages-container').on('click', '.last a', function(){
			window.location.href = $(this).attr('href');
			return false;
		});
		$('#faycms-message').on('click', '.delete-message-link', function(){
			var _this = $(this);
			$.ajax({
				type: 'GET',
				url: system.url('admin/notification/delete', {'id':_this.attr('data-id')}),
				dataType: 'json',
				cache: false,
				success: function(data){
					if(data.status == 1){
						_this.parent().css('background-color', 'red').fadeOut('slow', function(){
							$(this).remove();
						});
					}else{
						common.alert(data.message);
					}
					common.headerNotification();
				}
			});
			return false;
		}).on('click', '#faycms-messages-container', function(){
			return false;
		}).on('click', '.set-read-link', function(){
			var _this = $(this);
			$.ajax({
				type: 'GET',
				url: system.url('admin/notification/set-read'),
				data: {
					'id':_this.attr('data-id'),
					'read': 1
				},
				dataType: 'json',
				cache: false,
				success: function(data){
					if(data.status == 1){
						_this.parent().css('background-color', 'orange').fadeOut('slow', function(){
							$(this).remove();
						})
					}else{
						common.alert(data.message);
					}
					common.headerNotification();
				}
			});
			return false;
		});
	},
	'headerNotification': function(){
		$.ajax({
			type: 'GET',
			url: system.url('admin/notification/get'),
			dataType: 'json',
			cache: false,
			success: function(resp){
				$('#faycms-messages .faycms-message-item').remove();
				if(resp.data.length){
					$('#faycms-message .badge').text(resp.data.length).show();
					var has_new_message = false;
					$.each(resp.data, function(i, data){
						if(new Date().getTime() - data.publish_time * 1000 < 50000){
							//50秒内有新信息，自动弹出
							has_new_message = true;
						}
						$('#faycms-messages').append([
							'<li class="faycms-message-item"><span class="faycms-message-container">',
								'<span class="block">',
									'<span class="fc-grey small">', system.shortDate(data.publish_time),'</span>',
								'</span>',
								'<span class="ellipsis" title="', data.title, '">', data.title, '</span>',
								'<a href="javascript:;" class="set-read-link fa fa-bell-slash" data-id="', data.notification_id, '" title="标记为已读"></a>',
								'<a href="javascript:;" class="delete-message-link" data-id="', data.notification_id, '" title="删除">×</a>',
							'</span></li>'
						].join(''));
					});
					
					system.getScript(system.assets('js/jquery.slimscroll.min.js'), function(){
						$('#faycms-messages').slimScroll({
							'height': '300px',
							'color': '#a1b2bd',
							'opacity': .3
						});
					});
					if(has_new_message){
						$('#faycms-message').addClass('open');
					}
				}else{
					$('#faycms-messages').prepend([
						'<li class="faycms-message-item"><span class="faycms-message-container">',
							'<span class="ellipsis" title="">暂无未读信息</span>',
						'</span></li>'
					].join(''));
					$('#faycms-message .badge').text(0).hide();
				}
			}
		});
	},
	'menu': function(){
		$('#main-menu').on('click', '.has-sub > a', function(){
			//非顶级菜单，或非缩起状态，或者屏幕很小（本来是大的，菜单缩起后变小）,或者IE8（因为IE8下折叠没效果）
			if($(this).parent().parent().parent().hasClass('has-sub') || !$('#sidebar-menu').hasClass('collapsed') || $(window).width() < 768 || ($.browser.msie && $.browser.version == '8.0')){
				var slideElapse = 300;//滑动效果持续
				$li = $(this).parent();//父级li
				$ul = $(this).next('ul');//子菜单的ul
				$_li = $ul.children('li');//子菜单的li
				if($li.hasClass('expanded')){
					//关闭
					$ul.slideUp(slideElapse, function(){
						$li.removeClass('expanded').removeClass('opened');
						$(this).removeAttr('style');
					});
				}else{
					//打开
					$ul.slideDown(slideElapse, function(){
						$(this).removeAttr('style');
					});
					$li.addClass('expanded');
					$_li.addClass('is-hidden');
					setTimeout((function($li){
						return function(){
							$li.addClass('is-shown');
						}
					})($_li), 0);
					setTimeout((function($li){
						return function(){
							$li.removeClass('is-hidden is-shown');
						}
					})($_li), 500);
					
					//关闭其它打开的同辈元素
					$li.siblings('.expanded').children('ul').slideUp(slideElapse, function(){
						$li.siblings('.expanded').removeClass('expanded').removeClass('opened');
						$(this).removeAttr('style');
					});
				}
				return false;
			}
		});
		
		//小屏幕下打开关闭上面的菜单
		$('.toggle-mobile-menu').on('click', function(){
			$('#main-menu').toggleClass('mobile-is-visible');
		});
		
		//打开缩起左侧菜单
		$('.toggle-sidebar').on('click', function(){
			$('#sidebar-menu').toggleClass('collapsed');
			$(window).resize();
			$.ajax({
				type: 'POST',
				url: system.url('admin/system/setting'),
				data: {
					'_key': 'admin_sidebar_class',
					'class':$('#sidebar-menu').hasClass('collapsed') ? 'collapsed' : ''
				}
			});
		});
		
		//左侧菜单固定
		if($('.sidebar-menu').hasClass('fixed') && !($.browser.msie && $.browser.version < 9)){
			//插件不支持IE8
			system.getCss(system.assets('css/perfect-scrollbar.css'), function(){
				system.getScript(system.assets('js/perfect-scrollbar.js'), function(){
					if(parseInt($(window).width()) > 768 && !$('.sidebar-menu').hasClass('collapsed')){
						$('.sidebar-menu-inner').perfectScrollbar({
							'wheelPropagation': true
						});
					}
					$(window).resize(function(){
						if(parseInt($(window).width()) > 768 && !$('.sidebar-menu').hasClass('collapsed')){
							$('.sidebar-menu-inner').perfectScrollbar({
								'wheelPropagation': true
							});
						}else{
							$('.sidebar-menu-inner').perfectScrollbar('destroy');
						}
					});
				});
			});
		}else{
			//ie8的情况下移除fixed
			$('.sidebar-menu').removeClass('fixed');
		}
	},
	'screenMeta': function(){
		if($('.screen-meta-links a').length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
				system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
					$('.screen-meta-links > a').fancybox({
						'padding': 0,
						'centerOnScroll': true,
						'titleShow': false,
						'onClosed': function(o){
							$($(o).attr('href')).find('input,select,textarea').each(function(){
								$(this).poshytip('hide');
							});
						}
					});
				});
			});
			
			
			$('.faycms-setting-link').on('mouseover', function(){
				$(this).addClass('fa-spin');
			}).on('mouseleave', function(){
				$(this).removeClass('fa-spin');
			});
		}
	},
	'dragsort': function(){
		//box的拖拽
		if($('.dragsort').length){
			system.getScript(system.assets('js/jquery.dragsort-0.5.1.js'), function(){
				$('.dragsort').dragsort({
					'itemSelector': 'div.box',
					'dragSelector': 'h4',
					'dragBetween': true,
					'placeHolderTemplate': '<div class="box holder"></div>',
					'dragSelectorExclude': 'input,textarea,select,table,span,p',
					'dragEnd': function(){
						//拖拽后poshytip需要重新定位
						$('.dragsort').find('input,select,textarea').each(function(){
							if($(this).data('poshytip')){
								$(this).poshytip('refresh');
							}
						});
						
						//若设置了key，则发ajax保存当前排序
						if(common.dragsortKey){
							var data = {
								'_key':common.dragsortKey
							};
							$('.dragsort').each(function(){
								var sort = [];
								$(this).find('.box').each(function(){
									if($(this).attr('data-name')){
										sort.push($(this).attr('data-name'));
									}
								});
								data[$(this).attr('id')] = sort;
							});
							$.ajax({
								type: 'POST',
								url: system.url('admin/system/setting'),
								data: data,
								dataType: 'json',
								cache: false,
								success: function(resp){
									if(resp.status){
										
									}else{
										common.alert(resp.message);
									}
								}
							});
						}
					}
				});
			});
		}
	},
	'poshytip': function(){
		if($('.poshytip').length){
			system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
			system.getScript(system.assets('js/jquery.poshytip.min.js'), function(){
				$('.poshytip').poshytip({
					'className': 'tip-twitter',
					'alignTo': 'target',
					'alignX': 'center',
					'showTimeout': 5
				});
			});
		}
	},
	'fixContent': function(){
		//下拉后顶部固定
		if($('.fixed-content').length){
			system.getScript(system.assets('faycms/js/fayfox.fixcontent.js'), function(){
				$('.fixed-content').fixcontent();
			});
		}
	},
	'events': function(){
		//鼠标划过添加hover
		$(document).on('mouseenter', '.box-title, .toggle-hover, .list-table tr', function(){
			$(this).addClass('hover');
		}).on('mouseleave', '.box-title, .toggle-hover, .list-table tr', function (){
			$(this).removeClass('hover');
		});
		//打开关闭box
		$(document).on('click', '.box-title .toggle', function(){
			$(this).parent().parent().toggleClass('closed');
		});
		$(document).on('click', '.box-title .remove', function(){
			var $box = $(this).parent().parent();
			$box.slideUp('normal', function(){
				$(this).remove();
			});
			var $setting_item = $("#setting-form input[name='boxes[]'][value='"+$box.attr('data-name')+"']");
			if($setting_item.length){
				$setting_item.attr('checked', false);
				$('#setting-form').submit();
			}
		});
		//表单提交
		$(document).on('click', 'a[id$="submit"]', function(){
			$('form#'+$(this).attr('id').replace('-submit', '')).find('input[name="_export"]').remove();
			$('form#'+$(this).attr('id').replace('-submit', '')).append('<input type="hidden" name="_submit" value="'+$(this).attr('id')+'">')
			$('form#'+$(this).attr('id').replace('-submit', '')).submit();
			return false;
		});
		//表单导出
		$(document).on('click', 'a[id$="export"]', function(){
			$('form#'+$(this).attr('id').replace('-export', ''))
				.append('<input type="hidden" name="_export" value="1" />').submit();
			return false;
		});
		//重置表单
		$('a[id$="reset"]').click(function(){
			$('form#'+$(this).attr('id').replace('-reset', ''))[0].reset();
		});
		//表格间隔色
		$('.list-table').each(function(){
			$(this).find('tr:even').addClass('alternate');
		});
		//永久删除的确认
		$(document).on('click', '.remove-link', function(){
			return confirm('确实要永久删除此记录吗？');
		});
	},
	'pager': function(){
		//初始化页码输入框长度
		$('.pager .pager-input').each(function(){
			var inputLength = parseInt($(this).val().length);
			if(inputLength > 1){
				$(this).css({'width': 50 + 7 * (inputLength - 1)});
			}
		});
		
		//分页条页面跳转，输入框长度根据页码数长度变化
		//若要自定义ajax分页，可以把事件绑定到其它元素后return false防止页面跳转
		$(document).on('keyup', '.pager .pager-input', function(event){
			if(event.keyCode == 13 || event.keyCode == 108){
				var link = window.location.href;
				if(link.indexOf('?') > 0){
					window.location.href = link+'&'+$(this).attr('name')+'='+$(this).val();
				}else{
					window.location.href = link+'?'+$(this).attr('name')+'='+$(this).val();
				}
			}else{
				if($(this).val() > $(this).attr('max')){
					$(this).val($(this).attr('max'));
				}
				var inputLength = parseInt($(this).val().length);
				if(inputLength){
					$(this).css({'width': 50 + 7 * (inputLength - 1)});
				}else{
					//输入了非数字，会获取不到
					$(this).css({'width': 50}).val('');
				}
			}
		});
	},
	'validform': function(){
		return false;
		if($('form.validform').length){
			system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
				if(!$.isEmptyObject(common.validformParams.forms)){
					for(var k in common.validformParams.forms){
						if(common.validformParams.forms[k].scene == 'default'){
							var formId = 'form';
						}else{
							var formId = common.validformParams.forms[k].scene + '-form';
						}
						var settings = common.validformParams.settings[common.validformParams.forms[k].model];
						settings.init();
						common.validformParams.forms[k].obj = $('form#'+formId).validform(settings, common.validformParams.forms[k].rules, common.validformParams.forms[k].labels);
					}
				}
				//剩余的一些可能是手工指定的form
				var settings = common.validformParams.settings.poshytip;
				settings.init();
				$('form.validform').validform(settings);
			});
		}
	},
	'datepicker': function(){
		//完整的时间选择，精确到秒
		if($('.timepicker').length){
			system.getScript(system.assets('js/My97DatePicker/WdatePicker.js'), function(){
				$(document).on('focus', '.timepicker', function(){
					WdatePicker({
						'dateFmt': 'yyyy-MM-dd HH:mm:ss'
					});
				});
			});
		}
		
		if($('input.datetimepicker').length){
			system.getCss(system.assets('js/datetimepicker/jquery.datetimepicker.css'));
			system.getScript(system.assets('js/datetimepicker/jquery.datetimepicker.js'), function(){
				$('input.datetimepicker').datetimepicker({
					'lang': 'ch',
					'format': 'Y-m-d H:i:00',
					'formatDate': 'Y-m-d',
					'formatTime': 'H:i',
					'dayOfWeekStart': 1,
					'yearStart': 2010,
					'yearEnd': 2037,
					'allowBlank':($.browser.msie && $.browser.version < 9) ? false : true,
					'onShow': function(ct, e){
						//原插件的定位稍微偏高了一点，且没地方可以配置，只好这样了
						setTimeout((function(o){
							return function(){
								var top = parseInt($(o).css('top'));
								if(top){
									$(o).css('top', top + 2);
								}
							}
						})(this), 20);
						
						//清空最大最小值限制
						this.setOptions({
							maxDate: false,
							maxTime: false,
							minDate: false,
							minTime: false
						});
						//若控件是用在起止时间选择的场景
						var name = $(e).attr('name');
						if(name){
							if(name.indexOf('start_time') === 0){
								var end_time = $(e).parent().find('input.datetimepicker[name^="end_time"]').val();
								if(end_time){
									this.setOptions({
										maxDate: end_time.split(' ')[0]
									});
								}
							}else if(name.indexOf('end_time') === 0){
								var start_time = $(e).parent().find('input.datetimepicker[name^="start_time"]').val();
								if(start_time){
									this.setOptions({
										minDate: start_time.split(' ')[0]
									});
								}
							}
						}
					}
				});
			});
		}
	},
	'visualEditor': function(){
		//此方法仅支持只有一个富文本编辑器的页面
		if($('#visual-editor').length){
			window.CKEDITOR_BASEPATH = system.assets('js/ckeditor/');
			system.getScript(system.assets('js/ckeditor/ckeditor.js'), function(){
				//清空table的一些默认设置
				CKEDITOR.on('dialogDefinition', function(ev){
					var dialogName = ev.data.name;
					var dialogDefinition = ev.data.definition;

					if (dialogName == 'table'){
						var info = dialogDefinition.getContents('info');

						info.get('txtWidth')['default'] = '';
						info.get('txtBorder')['default'] = '';
						info.get('txtCellSpace')['default'] = '';
						info.get('txtCellPad')['default'] = '';
						info.get('txtCols')['default'] = '3';
						info.get('selHeaders')['default'] = 'row';
					}
				});
				
				var config = {
					'height':$('#visual-editor').height()
				};
				if(common.filebrowserImageUploadUrl){
					config.filebrowserImageUploadUrl = common.filebrowserImageUploadUrl;
				}
				if(common.filebrowserFlashUploadUrl){
					config.filebrowserFlashUploadUrl = common.filebrowserFlashUploadUrl;
				}
				if($('#visual-editor').hasClass('visual-simple') || parseInt($(window).width()) < 743){
					//简化模式
					config.toolbar = [
				  		['Source'],
						['TextColor','BGColor'],
						['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],
				  		['Image','Table']
			  		];
					//简化模式回车设为br而非p
					config.enterMode = CKEDITOR.ENTER_BR;
					config.shiftEnterMode = CKEDITOR.ENTER_P;
				}
				common.editorObj = CKEDITOR.replace('visual-editor', config);
			});
		}
	},
	'markdownEditor': function(){
		//Markdown编辑器
		if($('#wmd-input').length){
			system.getCss(system.assets('css/pagedown.css'));
			system.getScript(system.assets('js/markdown/Markdown.Converter.js'), function(){
				system.getScript(system.assets('js/markdown/Markdown.Sanitizer.js'), function(){
					system.getScript(system.assets('js/markdown/Markdown.Editor.js'), function(){
						system.getScript(system.assets('js/markdown/Markdown.Extra.js'), function(){
							system.getScript(system.assets('js/prettify.js'), function(){
								$('#wmd-input').wrap('<div class="wmd-panel"></div>')
									.before(['<div id="wmd-button-bar">',
										'<div id="wmd-model-links">',
											'<a href="javascript:;" id="wmd-edit-model">编辑模式</a>',
											'<a href="javascript:;" id="wmd-live-model">实况模式</a>',
											'<a href="javascript:;" id="wmd-preview-model">预览模式</a>',
										'</div>',
									'</div>'].join(''))
									.addClass('fl')
									.parent().after('<div id="wmd-preview" class="wmd-preview h350"></div>');
								
								$('#wmd-button-bar').on('click', '#wmd-edit-model', function(){
									$('#wmd-input').show().css('width', '98.9%');
									$('#wmd-preview').hide();
								}).on('click', '#wmd-live-model', function(){
									$('#wmd-input').show().css('width', '49%');
									$('#wmd-preview').show().css('width', '48.7%');
								}).on('click', '#wmd-preview-model', function(){
									$('#wmd-input').hide();
									$('#wmd-preview').show().css('width', '98.9%');
								});
								
								var converter = new Markdown.Converter();
								Markdown.Extra.init(converter, {
									highlighter: "prettify"
								});

								var options = {
									strings: {
										bold: '加粗 <strong> Ctrl+B',
										boldexample: '加粗文字',
											
										italic: '斜体 <em> Ctrl+I',
										italicexample: '斜体文字',
	
										link: '链接 <a> Ctrl+L',
										linkdescription: '请输入链接描述',
	
										quote:  '引用 <blockquote> Ctrl+Q',
										quoteexample: '引用文字',
	
										code: '代码 <pre><code> Ctrl+K',
										codeexample: '请输入代码',
	
										image: '图片 <img> Ctrl+G',
										imagedescription: '请输入图片描述',
	
										olist: '数字列表 <ol> Ctrl+O',
										ulist: '普通列表 <ul> Ctrl+U',
										litem: '列表项目',
	
										heading: '标题 <h1>/<h2> Ctrl+H',
										headingexample: '标题文字',
	
										hr: '分割线 <hr> Ctrl+R',
										more: '摘要分割线 <!--more--> Ctrl+M',
	
										undo: '撤销 - Ctrl+Z',
										redo: '重做 - Ctrl+Y',
										redomac: '重做 - Ctrl+Shift+Z',
	
										fullscreen: '全屏 - Ctrl+J',
										exitFullscreen: '退出全屏 - Ctrl+E',
										fullscreenUnsupport: '此浏览器不支持全屏操作',
	
										imagedialog: '<p><b>插入图片</b></p><p>请在下方的输入框内输入要插入的远程图片地址</p><p>您也可以使用附件功能插入上传的本地图片</p>',
										linkdialog: '<p><b>插入链接</b></p><p>请在下方的输入框内输入要插入的链接地址</p>',
	
										ok: '确定',
										cancel: '取消',
	
										help: 'Markdown语法帮助'
									}
								};
								common.editorObj = new Markdown.Editor(converter, '', options);
								common.editorObj.hooks.chain('onPreviewRefresh', prettyPrint);
								common.editorObj.run();
							});
						});
					});
				});
			});
		}
	},
	'removeEditor': function(){//移除富文本编辑器实例
		common.editorObj.destroy();
	},
	'tab': function(){
		$(document).on('click', '.tabbable .nav-tabs a', function(){
			if(!$(this).parent().hasClass('active')){
				//如果被点击的tab不是当前tab，则先把当前tab对应div中的poshytip清掉
				$($(this).parent().siblings('.active').find('a').attr('href')).find('input,select,textarea').each(function(){
					$(this).poshytip('hide');
				});
			}
			$($(this).attr('href')).show().siblings().hide();
			$(this).parent().addClass('active').siblings().removeClass('active');
			
			return false;
		});
		
		var hash = window.location.hash;
		$('.tabbable').each(function(){
			//url中有指定锚点，且本实例中包含此锚点，打开指定锚点的tab
			if($(this).find('.nav-tabs a[href="'+hash+'"]').length){
				$(this).find('.nav-tabs a[href="'+hash+'"]').click();
			}else if($(this).find('.nav-tabs li.active').length){
				//有指定打开那个tab的class，打开对应的tab
				$(this).find('.nav-tabs li.active a').click();
			}else{
				//默认打开第一个tab
				$(this).find('.nav-tabs li:first a').click();
			}
		});
	},
	'showPager': function(id, pager){
		if(pager.total_pages > 1){
			var html = ['<span class="summary">', pager.total_records, '条记录</span>'];
			//向前导航
			if(pager.current_page == 1){
				html.push('<a href="javascript:;" title="首页" class="page-numbers first disabled">&laquo;</a>');
				html.push('<a href="javascript:;" title="上一页" class="page-numbers prev disabled">&lsaquo;</a>');
			}else{
				html.push('<a href="javascript:;" title="首页" class="page-numbers first" data-page="1">&laquo;</a>');
				html.push('<a href="javascript:;" title="上一页" class="page-numbers prev" data-page="' + (pager.current_page - 1) + '">&lsaquo;</a>');
			}
			
			//页码输入框
			html.push(' 第 <input type="number" value="' + pager.current_page + '" class="form-control pager-input" min="1" max="' + pager.total_pages + '" /> 页，共' + pager.total_pages + '页');
			
			//向后导航
			if(pager.current_page == pager.total_pages){
				html.push('<a href="javascript:;" title="下一页" class="page-numbers prev disabled">&rsaquo;</a>');
				html.push('<a href="javascript:;" title="末页" class="page-numbers first disabled">&raquo;</a>');
			}else{
				html.push('<a href="javascript:;" title="下一页" class="page-numbers prev" data-page="' + (pager.current_page + 1) + '">&rsaquo;</a>');
				html.push('<a href="javascript:;" title="末页" class="page-numbers first" data-page="' + pager.total_pages + '">&raquo;</a>');
			}
		}else{
			var html = ['<span class="summary">', pager.total_records, '条记录</span>'];
		}
		$('#'+id).html(html.join(''));
	},
	'batch': function(){
		//批量操作表单提交
		$('body').on('change', '.batch-ids-all', function(){
			$('.batch-ids[disabled!="disabled"],.batch-ids-all').attr('checked', !!$(this).attr('checked'));
		}).on('click', '#batch-form-submit', function(){
			if($('#batch-action').val() == ''){
				common.alert('请选择操作');
			}else{
				$('#batch-form [name="batch_action"],#batch-form [name="_submit"]').remove();
				$('#batch-form').append('<input type="hidden" name="batch_action" value="'+$('#batch-action').val()+'">')
					.append('<input type="hidden" name="_submit" value="batch-form-submit">');
				$('body').block();
				$('#batch-form').submit();
			}
			return false;
		}).on('click', '#batch-form-submit-2', function(){
			if($('#batch-action-2').val() == ''){
				common.alert('请选择操作');
			}else{
				$('#batch-form [name="batch_action"],#batch-form [name="_submit"]').remove();
				$('#batch-form').append('<input type="hidden" name="batch_action" value="'+$('#batch-action-2').val()+'">')
					.append('<input type="hidden" name="_submit" value="batch-form-submit-2">');
				$('body').block();
				$('#batch-form').submit();
			}
			return false;
		});
	},
	'dragsortList': function(){
		if($('.dragsort-list').length){
			//可拖拽的列表，例如文章附件
			system.getScript(system.assets('js/jquery.dragsort-0.5.1.js'), function(){
				$(".dragsort-list").dragsort({
					'itemSelector': 'div.dragsort-item',
					'dragSelector': '.dragsort-item-selector',
					'placeHolderTemplate': '<div class="dragsort-item holder"></div>',
					'dragEnd': function(a, b, c){
						//拖拽后poshytip需要重新定位
						$('.dragsort-list').find('input,select,textarea').each(function(){
							if($(this).data('poshytip')){
								$(this).poshytip('refresh');
							}
						});
					}
				});
			});
			//删除
			$(".dragsort-list").on('click', '.dragsort-rm', function(){
				$(this).parent().fadeOut('fast', function(){
					//先复制出来，因为后面$(this)要被remove掉
					var dragsort_list = $(this).parent();
					//拖拽列表若有报错，该项内部所有poshytip信息将被删除
					$(this).find('input,select,textarea').poshytip('destroy');
					//移除指定项
					$(this).remove();
					//拖拽列表若有报错，该列表内所有poshytip元素将重新定位
					dragsort_list.find('input,select,textarea').each(function(){
						if($(this).data('poshytip')){
							$(this).poshytip('refresh');
						}
					});
				});
			});
		}
	},
	'textAutosize': function(){
		if($('textarea.autosize').length){
			system.getScript(system.assets('js/autosize.min.js'), function(){
				autosize($('textarea.autosize'));
			});
		}
	},
	'dropdown': function(){
		$(document).on('click', 'a.dropdown', function(){
			if($(this).parent().hasClass('open')){
				//关闭
				$(this).parent().removeClass('open');
			}else{
				//关闭所有其它下拉，打开当前下拉
				$('.dropdown-container').each(function(){
					if($(this).hasClass('open')){
						$(this).removeClass('open');
					}
				});
				$(this).parent().addClass('open');
			}
			return false;
		});
		$(document).on('click', function(){
			$('.dropdown-container').each(function(){
				if($(this).hasClass('open')){
					$(this).removeClass('open');
				}
			});
		});
	},
	'prettyPrint': function(){
		if($('.prettyprint').length){
			system.getScript(system.assets('js/prettify.js'), function(){
				prettyPrint();
			});
		}
	},
	'notify': function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'timeOut': 5000,
					'closeButton': false,
					'click': 'fadeOut',
					'positionClass': 'toast-top-right'
				});
			}else if(type == 'error'){
				//报错，也在右上角，显示关闭按钮，不消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-top-right'
				});
			}else if(type == 'alert'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': 'fadeOut'
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-right'
				});
			}
		});
	},
	'alert': function(message){
		this.notify(message, 'alert');
	},
	'init': function(){
		this.fancybox();
		this.menu();
		this.screenMeta();
		this.dragsort();
		this.poshytip();
		this.datepicker();
		this.batch();
		this.events();

		this.fixContent();
		this.validform();
		this.visualEditor();
		this.markdownEditor();
		this.tab();
		this.dragsortList();
		this.textAutosize();
		this.dropdown();
		this.notification();
		this.prettyPrint();
		this.pager();
	}
};