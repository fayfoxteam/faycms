var common = {
	'form':function(){
		//表单提交
		$(document).delegate("a[id$='submit']", 'click', function(){
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
			system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
			system.getScript(system.assets('js/jquery.poshytip.min.js'), function(){
				//只是引入，不做任何操作
			});
			
			system.getScript(system.assets('js/Validform_v5.3.2.js'), function(){
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
									'alignX': "inner-right",
									'offsetX': -60,
									'offsetY': 5,
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
						"date":/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/
					}
				});
			});
		}
	},
	'init':function(){
		this.form();
		this.placeholder();
		this.validform();
	}
};