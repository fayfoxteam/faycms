var group = {
	'direction': 'vertical',
	/**
	 * 判断手机是横着还是竖着
	 */
	'setDirection': function(){
		this.direction = document.documentElement.clientWidth > document.documentElement.clientHeight ? 'horizontal' : 'vertical';
	},
	/**
	 * 第一页
	 */
	'group1': function(){
		var width,//主体宽度
			height,//主体高度
			$group1 = $('#group-1'),
			$layer1 = $group1.find('.layer-1'),
			$layer1Img = $layer1.find('img')
		;
		if(this.direction == 'vertical'){
			//手机是竖着的
			width = document.documentElement.clientWidth * 0.6;
			height = width * $layer1Img.height() / $layer1Img.width();
		}else{
			//手机是横着的
			height = document.documentElement.clientHeight * 0.7;
			width = height * $layer1Img.width() / $layer1Img.height();
		}
		$layer1.width(width).css({'margin-left': - width / 2});
		$layer1.height(height).css({'margin-top': - height/ 2});
	},
	'init': function(){
		this.setDirection();
		this.group1();
		window.onresize = function(){
			group.init();
		};
	}
};