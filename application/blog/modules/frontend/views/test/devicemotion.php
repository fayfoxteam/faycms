<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.7.1.min.js')?>"></script>
</head>
<body>
<p><label>x轴加速度：</label><span id="acceleration-x"></span></p>
<p><label>y轴加速度：</label><span id="acceleration-y"></span></p>
<p><label>z轴加速度：</label><span id="acceleration-z"></span></p>
<p><label>速度：</label><span id="speed"></span></p>
<script>
//先判断设备是否支持HTML5摇一摇功能
if (window.DeviceMotionEvent) {
	//获取移动速度，得到device移动时相对之前某个时间的差值比
	window.addEventListener('devicemotion', deviceMotionHandler, false);
}else{
	alert('您好，你目前所用的设备好像不支持重力感应哦！');
}

var x, y, z, last_x, last_y, last_z;
var last_update = 0;
function deviceMotionHandler(event){
	//获得重力加速
	var acceleration =event.accelerationIncludingGravity;
	
	$('#acceleration-x').text(acceleration.x);
	$('#acceleration-y').text(acceleration.y);
	$('#acceleration-z').text(acceleration.z);
	
	var diffTime = curTime - last_update;
	last_update = curTime;
	x = acceleration.x;
	y = acceleration.y;
	z = acceleration.z;
	
	var speed = Math.abs(x +y + z - last_x - last_y - last_z) / diffTime * 10000;
	$('#speed').text(speed);
	last_x = x;
	last_y = y;
	last_z = z;
	
}
</script>
</body>
</html>