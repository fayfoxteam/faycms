/**
 * 当元素进入视野时触发回调函数
 */
jQuery.fn.extend({
	/**
	 * 选中元素进入视野触发回调
	 * @param callback
	 * @param offset 偏移量
	 *  - 若为正数，在offset像素进入视野后，触发回调
	 *  - 若为负数，则还差offset进入视野，就触发回调
	 */
	'scrollIn': function(callback, offset){
		offset = offset || 0;
		
		var _this = $(this);
		if(!_this.length){
			return _this;
		}
		var listen = function(){
			var scrollOffset = $(window).scrollTop();
			var elementOffset = _this.offset().top;
			var windowHeight = $(window).height();
			var elementHeight = _this.height();

			if(scrollOffset + windowHeight > elementOffset + offset && scrollOffset < elementOffset + elementHeight - offset){
				callback(_this);
			}
		};
		
		$(window).resize(function(){
			listen();
		});
		$(window).scroll(function(){
			listen();
		});
		$(document).ready(function(){
			listen();
		});
		
		return _this;
	},
	/**
	 * 选中元素离开视野后触发回调
	 * @param callback
	 * @param offset 偏移量
	 *  - 若为正数，在元素离开视野offset像素后触发回调
	 *  - 若为负数，在元素即将离开视野offset像素开始就触发回调
	 */
	'scrollOut': function(callback, offset){
		offset = offset || 0;
		
		var _this = $(this);
		if(!_this.length){
			return _this;
		}
		var listen = function(){
			var scrollOffset = $(window).scrollTop();
			var elementOffset = _this.offset().top;
			var windowHeight = $(window).height();
			var elementHeight = _this.height();

			if(scrollOffset > elementOffset + elementHeight + offset || scrollOffset + windowHeight < elementOffset - offset){
				console.log('out');
				callback(_this);
			}
		};

		$(window).resize(function(){
			listen();
		});
		$(window).scroll(function(){
			listen();
		});
		$(document).ready(function(){
			listen();
		});

		return _this;
	}
});