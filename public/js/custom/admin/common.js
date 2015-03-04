var common = {
	'filebrowserImageUploadUrl':null,	//可视化编辑器的文件上传路径
	'filebrowserFlashUploadUrl':null,	//可视化编辑器的Flash上传路径
	'dragsortKey':null,	//用于自动保存dragsort排序
	'validformParams':{
		'forms':{},
		'settings':{
			'poshytip':{//利用poshytip插件报错
				'init':function(){
					system.getCss(system.url('css/tip-twitter/tip-twitter.css'));
					system.getScript(system.url('js/jquery.poshytip.min.js'));
				},
				'showAllErrors':true,
				'beforeSubmit':function(){
					$('.wrapper').block();
				},
				'onError':function(obj, msg, rule){
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
				'onSuccess':function(obj){
					var last = $.validform.getElementsByName(obj).last();
					last.poshytip('destroy');
				}
			},
			'setting':{//管理员界面顶部设置表单
				'init':function(){
					system.getCss(system.url('css/tip-twitter/tip-twitter.css'));
					system.getScript(system.url('js/jquery.poshytip.min.js'));
				},
				'ajaxSubmit':true,
				'beforeSubmit':function(){
					$('#setting-form-submit').nextAll('span').remove();
					$('#setting-form-submit').after('<img src="'+system.url('images/throbber.gif')+'" class="submit-loading" />');
				},
				'afterAjaxSubmit':function(resp){
					if(resp.status){
						$('#setting-form-submit').nextAll('img,span,a').remove();
						$('#setting-form-submit').after('<span class="color-green" style="margin-left:6px;">保存成功，刷新页面后生效。</span><a href="javascript:window.location.reload();">点此刷新</a>');
					}else{
						alert(resp.message);
					}
				},
				'onError':function(obj, msg, rule){
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
				'onSuccess':function(obj){
					var last = $.validform.getElementsByName(obj).last();
					last.poshytip('destroy');
				}
			}
		}
	},
	'settingValidform':null,
	'beforeDragsortListItemRemove':function(obj){},//拖拽列表中有元素被删除前执行此回调函数，传入被删除元素的jquery对象
	'afterDragsortListItemRemove':function(obj){},//拖拽列表中有元素被删除前执行此回调函数，传入拖拽列表的jquery对象
	'fancybox':function(){
		//弹窗
		if($('.fancybox-image').length){
			system.getCss(system.url('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('.fancybox-image').fancybox({
					'transitionIn' : 'elastic',
					'transitionOut' : 'elastic',
					'type' : 'image',
					'padding':0,
					'centerOnScroll':true
				});
			});
		}
		if($('.fancybox-inline').length){
			system.getCss(system.url('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('.fancybox-inline').fancybox({
					'padding':0,
					'centerOnScroll':true,
					'onClosed':function(o){
						$($(o).attr('href')).find('input,select,textarea').each(function(){
							$(this).poshytip('hide');
						});
					},
					'type' : 'inline'
				});
			});
		}
		if($('.fancybox-iframe').length){
			system.getCss(system.url('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('.fancybox-iframe').fancybox({
					'centerOnScroll':true,
					'type' : 'iframe',
					'width' : 750,
					'autoDimensions' : true
				});
			});
		}
		if($('.fancybox-close').length){
			system.getCss(system.url('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(document).on('click', '.fancybox-close', function(){
					$.fancybox.close();
				});
			});
		}
		
	},
	'notification':function(){
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
		//ajax删除消息提示
		$(document).on('click', '.header-notification-delete', function(){
			$.ajax({
				type: 'GET',
				url: system.url('admin/notification/delete', {'id':$(this).attr('data-id')}),
				dataType: 'json',
				cache: false,
				success: function(data){
					if(data.status == 1){
						$('#header-notification-'+data.id).css('background-color', 'red').fadeOut();
					}else{
						alert(data.message);
					}
					common.headerNotification();
				}
			});
		});
	},
	'menu':function(){
		//左侧菜单条的打开关闭
		$(document).on('click', '.menu-head', function(){
			//菜单被折叠或菜单属于当前tab的时候，无效果
			if(!$('body').hasClass('folded') && !$(this).parent().hasClass('sel')){
				$(this).toggleClass('open');
				if($(this).hasClass('open')){
					$(this).next('ul').slideDown();
				}else{
					$(this).next('ul').slideUp('normal', function(){
						$(this).css('display', '');
					});
				}
			}
		});
		//左侧菜单条的显示隐藏
		$(document).on('click', '#collapse-menu', function(){
			$('body').toggleClass('folded');
			$.ajax({
				type: 'POST',
				url: system.url('admin/system/setting'),
				data: {
					'_key':'admin_body_class',
					'class':$('body').hasClass('folded') ? 'folded' : ''
				}
			});
		});
	},
	'screenMeta':function(){
		$('.screen-meta-links').on('click', 'a', function(){
			$(this).toggleClass('active');
			if($(this).hasClass('active')){
				$($(this).attr('href')).slideDown();
				$(this).parent()
					.css({
						'margin-top':'-1px'
					})
					.siblings()
					.css({
						'visibility':'hidden'
					})//隐藏其他设置项
					.find('a').removeClass('active');
					
			}else{
				$($(this).attr('href')).slideUp();
				$(this).parent()
					.css({
						'margin-top':''
					})
					.siblings()
					.css({
						'visibility':''
					});
			}
			return false;
		});
	},
	'dragsort':function(){
		//box的拖拽
		if($('.dragsort').length){
			system.getScript(system.url('js/jquery.dragsort-0.5.1.js'), function(){
				$('.dragsort').dragsort({
					'itemSelector': 'div.box',
					'dragSelector': 'h4',
					'dragBetween': true,
					'placeHolderTemplate': '<div class="box"></div>',
					'dragSelectorExclude': 'input,textarea,select,table,span,p',
					'dragEnd':function(){
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
										alert(resp.message);
									}
								}
							});
						}
					}
				});
			});
		}
	},
	'poshytip':function(){
		if($('.poshytip').length){
			system.getCss(system.url('css/tip-twitter/tip-twitter.css'));
			system.getScript(system.url('js/jquery.poshytip.min.js'), function(){
				$('.poshytip').poshytip({
					'className':'tip-twitter',
					'alignTo': 'target',
					'alignX': 'center',
					'showTimeout':5
				});
			});
		}
	},
	'fixContent':function(){
		//下拉后顶部固定
		if($('.fixed-content').length){
			system.getScript(system.url('js/custom/fayfox.fixcontent.js'), function(){
				$('.fixed-content').fixcontent();
			});
		}
	},
	'events':function(){
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
		//label覆盖到输入框上面
		$('.titlediv').each(function(i){
			if($(this).find('input').val() != ''){
				$(this).find('.title-prompt-text').hide();
			}
		});
		$('.titlediv input').focus(function(){
			$(this).parent().find('.title-prompt-text').hide();
		}).blur(function(){
			if($(this).val()==''){
				$(this).parent().find('.title-prompt-text').show();
			}
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
	'validform':function(){
		if($('form.validform').length){
			system.getScript(system.url('js/custom/fayfox.validform.min.js'), function(){
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
	'datepicker':function(){
		//完整的时间选择，精确到秒
		if($('.timepicker').length){
			system.getScript(system.url('js/My97DatePicker/WdatePicker.js'), function(){
				$(document).on('focus', '.timepicker', function(){
					WdatePicker({
						'dateFmt':'yyyy-MM-dd HH:mm:ss'
					});
				});
			});
		}
		
		//跨度为10分钟的时间选择，不是很精确
		if($('.datetimepicker[name!="start_time"][name!="end_time"]').length){
			system.getCss(system.url('js/datetimepicker/jquery.datetimepicker.css'));
			system.getScript(system.url('js/datetimepicker/jquery.datetimepicker.js'), function(){
				$('.datetimepicker').datetimepicker({
					'lang':'ch',
					'format':'Y-m-d H:i:00',
					'formatDate':'Y-m-d',
					'formatTime':'H:i',
					'dayOfWeekStart':1,
					'allowBlank':$.browser.msie ? false : true,
					'onShow':function(){
						//原插件的定位稍微偏高了一点，且没地方可以配置，只好这样了
						setTimeout((function(o){
							return function(){
								var top = parseInt($(o).css('top'));
								if(top){
									$(o).css('top', top + 2);
								}
							}
						})(this), 20);
					}
				});
			});
		}
		
		if($('input.datetimepicker[name="start_time"]').length){
			system.getCss(system.url('js/datetimepicker/jquery.datetimepicker.css'));
			system.getScript(system.url('js/datetimepicker/jquery.datetimepicker.js'), function(){
				$('input.datetimepicker[name="start_time"]').datetimepicker({
					'lang':'ch',
					'format':'Y-m-d H:i:00',
					'formatDate':'Y-m-d',
					'formatTime':'H:i',
					'dayOfWeekStart':1,
					'allowBlank':($.browser.msie && $.browser.version < 9) ? false : true,
					'onShow':function(ct){
						//原插件的定位稍微偏高了一点，且没地方可以配置，只好这样了
						setTimeout((function(o){
							return function(){
								var top = parseInt($(o).css('top'));
								if(top){
									$(o).css('top', top + 2);
								}
							}
						})(this), 20);
						var end_time = $(this).parent().find('input.datetimepicker[name="end_time"]').val();
						if(end_time){
							this.setOptions({
								maxDate:end_time.split(' ')[0],
								maxTime:false
							});
						}
					}
				});
			});
		}
		
		if($('input.datetimepicker[name="end_time"]').length){
			system.getCss(system.url('js/datetimepicker/jquery.datetimepicker.css'));
			system.getScript(system.url('js/datetimepicker/jquery.datetimepicker.js'), function(){
				$('input.datetimepicker[name="end_time"]').datetimepicker({
					'lang':'ch',
					'format':'Y-m-d H:i:00',
					'formatDate':'Y-m-d',
					'formatTime':'H:i',
					'dayOfWeekStart':1,
					'allowBlank':($.browser.msie && $.browser.version < 9) ? false : true,
					'onShow':function(ct){
						//原插件的定位稍微偏高了一点，且没地方可以配置，只好这样了
						setTimeout((function(o){
							return function(){
								var top = parseInt($(o).css('top'));
								if(top){
									$(o).css('top', top + 2);
								}
							}
						})(this), 20);
						var start_time = $(this).parent().find('input.datetimepicker[name="start_time"]').val();
						if(start_time){
							this.setOptions({
								minDate:start_time.split(' ')[0],
								maxTime:false
							});
						}
					}
				});
			});
		}
	},
	'visualEditor':function(){
		//此方法仅支持只有一个富文本编辑器的页面
		if($('#visual-editor').length){
			if($.browser.msie && $.browser.version < 8){
				system.getScript(system.url('js/kindeditor/kindeditor.js'), function(){
					KindEditor.basePath = system.url('js/kindeditor/');
					system.getScript(system.url('js/kindeditor/lang/zh_CN.js'), function(){
						var config = {
							'width': '100%',
							'height': $('#visual-editor').height(),
							'filterMode': false,
							'formatUploadUrl': false,
							'items':[
								'source', 'preview', '|',
								'paste', 'plainpaste', 'wordpaste', 'undo', 'redo', '|',
								'link', 'unlink', '|',
								'image', 'table', 'emoticons', '|',
								'insertorderedlist', 'insertunorderedlist', 'outdent', 'indent', 'justifyleft', 'justifycenter', 'justifyright',
								'/',
								'formatblock', 'fontname', 'fontsize', '|',
								'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough','subscript', 'superscript', 'clearhtml', '|',
								'fullscreen', 'code'
							]
						};
						if(common.filebrowserImageUploadUrl){
							config.uploadJson = common.filebrowserImageUploadUrl;
						}
						common.editorObj = KindEditor.create('#visual-editor', config);
						//表单提交时获取编辑器内容
						$($('#visual-editor')[0].form).submit(function(){
							$('#visual-editor').val(common.editorObj.html());
						});
					});
				});
			}else{
				window.CKEDITOR_BASEPATH = system.url('js/ckeditor/');
				system.getScript(system.url('js/ckeditor/ckeditor.js'), function(){
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
					if($('#visual-editor').hasClass('visual-simple')){
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
					//console.log(config);
					common.editorObj = CKEDITOR.replace('visual-editor', config);
				});
			}
		}
	},
	'markdownEditor':function(){
		//Markdown编辑器
		if($('#wmd-input').length){
			system.getCss(system.url('css/pagedown.css'));
			system.getScript(system.url('js/markdown/Markdown.Converter.js'), function(){
				system.getScript(system.url('js/markdown/Markdown.Sanitizer.js'), function(){
					system.getScript(system.url('js/markdown/Markdown.Editor.js'), function(){
						system.getScript(system.url('js/markdown/Markdown.Extra.js'), function(){
							system.getScript(system.url('js/prettify.js'), function(){
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
	'removeEditor':function(){//移除富文本编辑器实例
		if($.browser.msie && $.browser.version < 8){
			KindEditor.remove(common.editorObj);
		}else{
			common.editorObj.destroy();
		}
	},
	'headerNotification':function(){
		$.ajax({
			type: 'GET',
			url: system.url('admin/notification/get'),
			dataType: 'json',
			cache: false,
			success: function(resp){
				$('#header-notification-count').text(resp.data.length);
				$('.header-notification-list').html('<ul></ul>');
				var has_new_message = false;
				$.each(resp.data, function(i, data){
					if(new Date().getTime() - data.publish_time * 1000 < 50000){
						//50秒内有新信息，自动弹出
						has_new_message = true;
					}
					$('.header-notification-list ul').append([
						'<li class="header-notification-item toggle-hover" id="header-notification-', data.id, '">',
							'<a href="javascript:;" class="header-notification-delete" data-id="', data.id, '" title="删除"></a>',
							'<div title="', system.encode(data.content), '">', system.encode(data.content), '</div>',
								'<span class="abbr time" title="', system.date(data.publish_time), '">', system.shortDate(data.publish_time), '</span>',
							'</div>',
						'</li>'
					].join(''));
				});
				if(resp.data.length > 0){
					$('.header-notification-list ul').append(['<li>',
						'<a href="javascript:;" class="header-notification-mute fl">不再提示</a>',
						'<a href="', system.url('admin/notification/my'), '" class="fr">更多</a>',
					'</li>'].join(''));
					if(has_new_message){
						$('.header-notification').addClass('hover');
					}
				}else{
					$('.header-notification').removeClass('hover');
				}
			}
		});
	},
	'tab':function(){
		$('.tabbable').each(function(){
			$(this).find('.tab-content div.tab-pane:gt(0)').hide();
		});
		$(document).on('click', '.tabbable .nav-tabs a', function(){
			$($(this).attr('href')).show().siblings().hide();
			$(this).parent().addClass('active').siblings().removeClass('active');
			
			return false;
		});
	},
	'showPager':function(id, pager){
		var html = ['<ul>'];
		html.push('<li class="summary">共&nbsp;', pager.totalRecords, '&nbsp;条记录，当前第&nbsp;', pager.startRecord, '&nbsp;到&nbsp;', pager.endRecord, '&nbsp;条</li>');
		//上一页
		if(pager.currentPage == 1 && pager.totalRecords != 0){
			html.push('<li><a class="prev disabled" href="javascript:;">«</a></li>');
		}else if(pager.totalRecords != 0){
			html.push('<li><a class="prev" href="javascript:;" data-page="'+(pager.currentPage - 1)+'">«</a></li>');
		}
		//是否显示第一页
		if(pager.currentPage > pager.adjacents + 1){
			html.push('<li><a class="prev" href="javascript:;" data-page="1">1</a></li>');
		}
		//显示间隔
		if(pager.currentPage > pager.adjacents + 2){
			html.push('<li><a href="javascript:;">...</a></li>');
		}
		//显示页码条
		var pmin = pager.currentPage > pager.adjacents ? pager.currentPage - pager.adjacents : 1;
		var pmax = pager.currentPage < pager.totalPages - pager.adjacents ? pager.currentPage + pager.adjacents : pager.totalPages;
		
		for(var i = pmin; i <= pmax; i++){
			if(i == pager.currentPage){
				html.push('<li><a href="javascript:;" class="page action">'+i+'</a></li>');
			}else if(i == 1) {
				html.push('<li><a href="javascript:;" data-page="1">1</a></li>');
			}else{
				html.push('<li><a href="javascript:;" class="page" data-page="'+i+'">'+i+'</a></li>');
			}
		}
		//显示间隔
		if(pager.currentPage<(pager.totalPages-pager.adjacents-1)) {
			html.push('<li><a href="javascript:;">...</a></li>');
		}
		
		//显示最后一页
		if(pager.currentPage < pager.totalPages - pager.adjacents) {
			html.push('<li><a href="javascript:;" data-page="'+pager.totalPages+'">'+pager.totalPages+'</a></li>');
		}
		//下一页
		if(pager.currentPage < pager.totalPages) {
			html.push('<li><a class="next" href="javascript:;" title="下一页" data-page="'+(pager.currentPage + 1)+'">»</a></li>');
		}else if(pager.totalRecords != 0){
			html.push('<li><a class="next disabled" href="javascript:;">»</a></li>');
		}
		
		html.push('</ul>');
		$('#'+id).html(html.join(''));
	},
	'batch':function(){
		//批量操作表单提交
		$(document).on('change', '.batch-ids-all', function(){
			$('.batch-ids,.batch-ids-all').attr('checked', !!$(this).attr('checked'));
		}).on('submit', '#batch-form', function(){
			if($('[name="batch_action"]').val() == '' && $('[name="batch_action_2"]').val() == ''){
				alert('请选择操作');
				return false;
			}else{
				$('.wrapper').block();
			}
		}).on('click', '#batch-form-submit-2', function(){
			$('#batch-form-submit').click();
		});
	},
	'dragsortList':function(){
		if($('.dragsort-list').length){
			//可拖拽的列表，例如文章附件
			system.getScript(system.url('js/jquery.dragsort-0.5.1.js'), function(){
				$(".dragsort-list").dragsort({
					'itemSelector': 'div.dragsort-item',
					'dragSelector':'.dragsort-item-selector',
					'placeHolderTemplate': '<div class="dragsort-item holder"></div>'
				});
			});
			//删除
			$(".dragsort-list").on('click', '.dragsort-rm', function(){
				$(this).parent().fadeOut('fast', function(){
					var dragsort_list = $(this).parent();
					common.beforeDragsortListItemRemove($(this));
					$(this).remove();
					common.afterDragsortListItemRemove(dragsort_list);
				});
			});
		}
	},
	'textAutosize':function(){
		if($('textarea.autosize').length){
			system.getScript(system.url('js/jquery.autosize.min.js'), function(){
				$('textarea.autosize').autosize();
			});
		}
	},
	'init':function(){
		this.fancybox();
		this.notification();
		this.menu();
		this.screenMeta();
		this.dragsort();
		this.poshytip();
		this.datepicker();
		this.events();

		this.fixContent();
		this.validform();
		this.visualEditor();
		this.markdownEditor();
		this.tab();
		this.batch();
		this.dragsortList();
		this.textAutosize();
	}
};