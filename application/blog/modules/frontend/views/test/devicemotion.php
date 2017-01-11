<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.7.1.min.js')?>"></script>
</head>
<body>
<p><label>x轴加速度：</label><span id="acceleration-x"></span></p>
<p><label>y轴加速度：</label><span id="acceleration-y"></span></p>
<p><label>z轴加速度：</label><span id="acceleration-z"></span></p>
<script>
//先判断设备是否支持HTML5摇一摇功能
if (window.DeviceMotionEvent) {
	//获取移动速度，得到device移动时相对之前某个时间的差值比
	window.addEventListener('devicemotion', deviceMotionHandler, false);
}else{
	alert('您好，你目前所用的设备好像不支持重力感应哦！');
}

function deviceMotionHandler(event){
	//获得重力加速
	var acceleration =event.accelerationIncludingGravity;
	
	$('#acceleration-x').text(acceleration.x);
	$('#acceleration-y').text(acceleration.y);
	$('#acceleration-z').text(acceleration.z);
}
</script>
</body>
</html>