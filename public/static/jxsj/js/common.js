var common = {
	'form':function(){
		//表单提交
		$(document).on('click', "a[id$='submit']", function(){
			$("form#"+$(this).attr("id").replace("-submit", "")).submit();
			return false;
		});
	},
	'placeholder':function(){
		$(".form-field").each(function(i){
			if($(this).find("input").val() != ""){
				$(this).find(".prompt-text").hide();
			}
		});
		$(".form-field input").focus(function(){
			$(this).parent().find(".prompt-text").hide();
		}).blur(function(){
			if($(this).val()==""){
				$(this).parent().find(".prompt-text").show();
			}
		});
	},
	'validform':function(){
		if($("form.validform").length){
			//表单验证
			system.getCss(system.url('css/tip-twitter/tip-twitter.css'));
			system.getScript(system.url('js/jquery.poshytip.min.js'), function(){
				//只是引入，不做任何操作
			});
			
			system.getScript(system.url('js/Validform_v5.3.2.js'), function(){
				$("form.validform").Validform({
					showAllError:true,
					tipSweep:common.validform.tipSweep,
					tiptype:function(msg,o,cssctl){
						if(!o.obj.is("form")){
							//o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
							//先destroy掉
							$(o.obj).poshytip("destroy");
							if(o.type == 2 || o.type == 4){
								//通过验证，无操作
							}else{
								//报错
								$(o.obj).poshytip({
									'className': "tip-twitter",
									'showOn': "none",
									'alignTo': "target",
									'alignX': "right",
									'alignY': "center",
									'content': msg
								})
								.poshytip("show");
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
						"date":/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/,
						"idcard":function(gets,obj,curform,datatype){
							var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子;
							var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值，10代表X;
						
							if (gets.length == 15) {   
								return isValidityBrithBy15IdCard(gets);   
							}else if (gets.length == 18){   
								var a_idCard = gets.split("");// 得到身份证数组   
								if (isValidityBrithBy18IdCard(gets)&&isTrueValidateCodeBy18IdCard(a_idCard)) {   
									return true;   
								}   
								return false;
							}
							return false;
							
							function isTrueValidateCodeBy18IdCard(a_idCard) {   
								var sum = 0; // 声明加权求和变量   
								if (a_idCard[17].toLowerCase() == 'x') {   
									a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作   
								}   
								for ( var i = 0; i < 17; i++) {   
									sum += Wi[i] * a_idCard[i];// 加权求和   
								}   
								valCodePosition = sum % 11;// 得到验证码所位置   
								if (a_idCard[17] == ValideCode[valCodePosition]) {   
									return true;   
								}
								return false;   
							}
							
							function isValidityBrithBy18IdCard(idCard18){   
								var year = idCard18.substring(6,10);   
								var month = idCard18.substring(10,12);   
								var day = idCard18.substring(12,14);   
								var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
								// 这里用getFullYear()获取年份，避免千年虫问题   
								if(temp_date.getFullYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){   
									return false;   
								}
								return true;   
							}
							
							function isValidityBrithBy15IdCard(idCard15){   
								var year =  idCard15.substring(6,8);   
								var month = idCard15.substring(8,10);   
								var day = idCard15.substring(10,12);
								var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
								// 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法   
								if(temp_date.getYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){   
									return false;   
								}
								return true;
							}
						}
					}
				});
			});
		}
	},
	'datepicker':function(){
		if($(".datepicker").length){
			system.getCss(system.url('js/datetimepicker/jquery.datetimepicker.css'));
			system.getScript(system.url('js/datetimepicker/jquery.datetimepicker.js'), function(){
				$(".datepicker").datetimepicker({
					'lang':'ch',
					'format':'Y-m-d',
					'formatDate':'Y-m-d',
					'timepicker':false,
					'dayOfWeekStart':1,
					'yearOffset':-25
				});
			});
		}
	},
	'selfAdaption':function(){
		if(document.documentElement.clientHeight > document.body.clientHeight){
			$(".fix-footer").css({
				'position':'absolute',
				'bottom':0,
				'width':'100%'
			});
		}else{
			$(".fix-footer").css({
				'position':'static',
				'bottom':0,
				'width':'100%'
			});
		}
	},
	'init':function(){
		this.form();
		this.placeholder();
		this.validform();
		this.datepicker();
		this.selfAdaption();
		$(window).resize(function(){
			common.selfAdaption();
		});
	}
};