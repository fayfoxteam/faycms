<?php
use fay\models\Option;
?>
<div class="container">
	<div class="g-mn">
		<h1 class="sec-title"><span>联系我们</span></h1>
		
		<div id="contact-map" style="height:358px;"></div>
		
		<div id="contact-page" class="clearfix">
			<?php echo $page['content']?>
		</div>
		
		<div>
			<h3 class="sub-title">给我们发邮件</h3>
			<form method="post" action="<?php echo $this->url('contact/markmessage')?>" id="leave-message-form" class="validform">
				<fieldset>
					<div class="one-third fl">
						<label>称呼</label>
						<input type="text" name="name" datatype="*1-50" nullmsg="称呼不能为空" errormsg="称呼不能大于50个字符" />
					</div>
					<div class="one-third fl">
						<label>电话</label>
						<input type="text" name="phone" datatype="m" nullmsg="电话不能为空" errormsg="电话格式不正确" />
					</div>
					<div class="one-third fl">
						<label>邮箱</label>
						<input type="text" name="email" datatype="e" nullmsg="邮箱不能为空" errormsg="邮箱格式不正确" />
					</div>
					<div class="clear">
						<label>留言内容</label>
						<textarea name="message" datatype="*" nullmsg="留言不能为空"></textarea>
					</div>
					<a href="javascript:;" class="send fr" id="leave-message-form-submit">发送邮件</a>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>" />
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=10b033765ad00c668fcdd20902dab530"></script>
<script>
$(function(){
	// 百度地图API功能
	var map = new BMap.Map("contact-map");// 创建Map实例
	map.centerAndZoom(new BMap.Point(120.202605, 30.212051), 13);// 初始化地图,设置中心点坐标和地图级别
	map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
	map.addControl(new BMap.ScaleControl());// 添加比例尺控件
	map.enableScrollWheelZoom();//启用滚轮放大缩小

	var marker1 = new BMap.Marker(new BMap.Point(120.202605, 30.212051));// 创建标注
	map.addOverlay(marker1);              // 将标注添加到地图中

	//创建信息窗口
	var infoWindow1 = new BMap.InfoWindow("<?php echo Option::get('site.sitename')?>");
	marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});


	$("#leave-message-form-submit").on('click', function(){
		$("#leave-message-form").submit();
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