var common = {
	'validObj':null,
	'form':function(){
		//表单提交
		$(document).on('click', "a[id$='submit']", function(){
			$("form#"+$(this).attr("id").replace("-submit", "")).submit();
			return false;
		});
	},
	'validform':function(){
		if($("form.validform").length){
			//表单验证			
			system.getScript(system.assets('js/Validform_v5.3.2.js'), function(){
				common.validObj = $("form.validform").Validform({
					showAllError:true,
					tipSweep:common.validform.tipSweep,
					tiptype:function(msg,o,cssctl){
						if(!o.obj.is("form")){
							//o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
							//先destroy掉
							if(o.type == 2 || o.type == 4){
								//通过验证，无操作
								o.obj.parent().find('.tip').text('').removeClass('valid-error');
							}else{
								//报错
								o.obj.parent().find('.tip').text(msg).addClass('valid-error');
							}
						}
					},
					datatype : {
						"*":/[\w\W]+/,
						"*6-20":/^[\w\W]{6,20}$/,
						"n":/^\d+$/,
						"e":/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
						"m":/^13[0-9]{9}$|^14[0-9]{9}$|^15[0-9]{9}$|^18[0-9]{9}$/,
						"s2-20": /^[a-zA-Z_0-9-]{2,20}$/,
						"s1-255":/^[a-zA-Z_0-9-]{1,255}$/,
						"*1-255":/^[\w\W]{1,255}$/,
						"*1-50":/^[\w\W]{1,50}$/,
						"*1-100":/^[\w\W]{1,100}$/,
						"*1-32":/^[\w\W]{1,32}$/,
						"*1-500":/^[\w\W]{1,500}$/,
						"*1-30":/^[\w\W]{1,30}$/,
						"url":/^(http|https):\/\/\w+.*$/,
						"money":/^\d{1,7}(\.\d{1,2})?$/,
						"date":/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/
					}
				});
			});
		}
	},
	'fancybox':function(){
		//弹窗
		if($(".fancybox-image").length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".fancybox-image").fancybox({
					'transitionIn' : 'elastic',
					'transitionOut' : 'elastic',
					'type' : 'image',
					'padding':0,
					'centerOnScroll':true
				});
			});
		}
		if($(".fancybox-inline").length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".fancybox-inline").fancybox({
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
		if($(".fancybox-iframe").length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".fancybox-iframe").fancybox({
					'centerOnScroll':true,
					'type' : 'iframe',
					'width' : 750,
					'autoDimensions' : true
				});
			});
		}
		if($(".fancybox-close").length){
			system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
			system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(document).delegate(".fancybox-close", "click", function(){
					$.fancybox.close();
				});
			});
		}
		
	},
	//tab页
	'tab':function(){
		$(".tabbable").each(function(){
			$(this).find('.tab-content div.tab-pane:gt(0)').hide();
		});
		$(document).on('click', '.tabbable .nav-tabs a', function(){
			$($(this).attr("href")).show().siblings().hide();
			$(this).parent().addClass("active").siblings().removeClass("active");
			
			return false;
		});
	},
	//登陆检测
	'isLogin':function(){
		$('body').on('click', '.check-login', function(){
			if(system.user_id == 0){
				$(".login-link").click();
				return false;
			}
		});
	},
	//绑定登陆框事件
	'loginDialog':function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
		system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".login-link").fancybox({
				'transitionIn' : 'elastic',
				'transitionOut' : 'elastic',
				'type' : 'iframe',
				'padding':0,
				'centerOnScroll':true,
				'scrolling' : 'no',
				'width':437,
				'showCloseButton':false,
				'hideOnOverlayClick':false,
				'autoDimensions':true
			});
		});
	},
	//绑定注册框事件
	'registerDialog':function(){
		system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'));
		system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".register-link").fancybox({
				'transitionIn' : 'elastic',
				'transitionOut' : 'elastic',
				'type' : 'iframe',
				'padding':0,
				'centerOnScroll':true,
				'scrolling' : 'no',
				'width':437,
				'showCloseButton':false,
				'hideOnOverlayClick':false,
				'autoDimensions':true
			});
		});
	},
	'afterLogin':function(user){
		$.fancybox.close();
		$.ajax({
			'type': 'GET',
			'url': window.location.href,
			'cache': false,
			'success': function(resp){
				$('#g-hdu-links').html($('#g-hdu-links', resp).html());
			}
		});
	},
	'showError':function(msg){
		alert(msg);
	},
	'showSuccess':function(msg){
		alert(msg);
	},
	'showMsg':function(msg){
		alert(msg);
	},
	'init':function(){
		if(system.user_id == 0){
			this.loginDialog();
			this.registerDialog();
		}
		this.fancybox();
		this.isLogin();
		this.form();
		this.validform();
		this.tab();
	}
};