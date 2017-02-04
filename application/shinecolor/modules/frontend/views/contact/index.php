<div class="w1000 clearfix col-2">
	<div class="col-2-left">
		<nav class="left-menu">
			<ul>
				<li><a href="#contact">联系我们</a></li>
				<li><a href="#location">地理位置</a></li>
				<li><a href="#message">在线留言</a></li>
			</ul>
		</nav>
	</div>
	<div class="col-2-right">
		<div id="contact">
			<div id="contact-left">
				<img src="<?php echo $this->appAssets('images/contact-logo.png')?>" />
			</div>
			<div id="contact-right">
				<?php \F::widget()->load('contact')?>
			</div>
			<div class="clear"></div>
		</div>
		<div id="location">
			<h3>我们的地理位置</h3>
			<p>
				<img src="<?php echo $this->appAssets('images/location.jpg')?>" />
			</p>
		</div>
		<div id="message">
			<h3>在线留言</h3>
		</div>
		<div id="message">
			<form id="message-form" method="post" class="validform">
				<fieldset class="percent-one-third">
					<label>姓名</label>
					<input type="text" name="realname" datatype="*1-50" nullmsg="姓名不能为空" errormsg="姓名不能大于50个字符" />
				</fieldset>
				<fieldset class="percent-one-third">
					<label>邮箱</label>
					<input type="text" name="email" datatype="e" nullmsg="邮箱不能为空" errormsg="邮箱格式不正确" />
				</fieldset>
				<fieldset class="percent-one-third column-last">
					<label>电话</label>
					<input type="text" name="phone" datatype="m" nullmsg="电话不能为空" errormsg="电话格式不正确" />
				</fieldset>
				<fieldset class="clear">
					<label>留言</label>
					<textarea name="content" datatype="*" nullmsg="留言不能为空"></textarea>
				</fieldset>
				<fieldset class="clear">
					<a href="javascript:;" id="message-form-submit">提交留言</a>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo $this->assets('faycms/js/fayfox.fixcontent.js')?>"></script>
<script src="<?php echo $this->assets('js/jquery.scrollTo-min.js')?>"></script>
<script>
$(function(){
	$(".left-menu").fixcontent();
	$(".left-menu a").click(function(){
		$.scrollTo($(this).attr("href"), 500);
		return false;
	});

	$("#message-form-submit").click(function(){
		$("#message-form").submit();
	});

	system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
	system.getScript(system.assets('js/jquery.poshytip.min.js'), function(){
		//只是引入，不做任何操作
	});
	
	system.getScript(system.assets('js/Validform_v5.3.2.js'), function(){
		$("form.validform").Validform({
			showAllError:true,
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
});
</script>