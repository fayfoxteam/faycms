/**
 * 摇一摇插件
 */
;(function($){
	$.extend({
		'shake': function(callback, shakeThreshold){
			if(typeof(shakeThreshold) == 'undefined'){
				/*
				 * 数值越小，灵敏度越高
				 * - `1000`的话基本上一直在触发，没法用
				 * - `3000`差不多动一下会触发
				 * - `8000`需要稍微甩一下
				 */
				shakeThreshold = 8000;
			}

			if (window.DeviceMotionEvent) {
				//若设备不支持获取加速信息，则不做任何操作
				window.addEventListener('devicemotion', deviceMotionHandler, false);
			}

			var x, y, z, lastX, lastY, lastZ;
			var lastUpdate = 0;
			function deviceMotionHandler(event){
				//获得重力加速
				var acceleration =event.accelerationIncludingGravity;
				var curTime = new Date().getTime();
				var diffTime = curTime - lastUpdate;
				
				lastUpdate = curTime;
				x = acceleration.x;
				y = acceleration.y;
				z = acceleration.z;

				var speed = Math.abs(x + y + z - lastX - lastY - lastZ) / diffTime * 10000;
				if(speed > shakeThreshold){
					callback();
				}
				lastX = x;
				lastY = y;
				lastZ = z;
			}
		}
	})
})(jQuery);