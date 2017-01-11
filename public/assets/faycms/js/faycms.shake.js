/**
 * Toast插件，需要配合css
 */
;(function($){
	$.extend({
		'shake': function(callback, shakeThreshold){
			if(typeof(shakeThreshold) == 'undefined'){
				shakeThreshold = 3000;
			}

			if (window.DeviceMotionEvent) {
				//若设备不支持获取加速信息，则不做任何操作
				window.addEventListener('devicemotion', deviceMotionHandler, false);
			}

			var x, y, z, last_x, last_y, last_z;
			var last_update = 0;
			function deviceMotionHandler(event){
				//获得重力加速
				var acceleration =event.accelerationIncludingGravity;
				var curTime = new Date().getTime();

				$('#acceleration-x').text(acceleration.x);
				$('#acceleration-y').text(acceleration.y);
				$('#acceleration-z').text(acceleration.z);

				var diffTime = curTime - last_update;
				last_update = curTime;
				x = acceleration.x;
				y = acceleration.y;
				z = acceleration.z;

				var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
				if(speed > shakeThreshold){
					callback();
				}
				last_x = x;
				last_y = y;
				last_z = z;
			}
		}
	})
})(jQuery);