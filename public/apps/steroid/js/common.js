var common = {
	'animate': function(){
		system.getScript(system.assets('faycms/js/fayfox.scroll.js'), function(){
			//顶部固定菜单
			$('#section-banner').scrollOut(function(){
				$('.page-header-fixed').addClass('animated fadeInDown');
			}).scrollIn(function(){
				$('.page-header-fixed').removeClass('animated fadeInDown');
			});
			//商品列表延迟动画
			$('#section-products').scrollIn(function(o){
				$(o).addClass('animated fadeIn');
			}, 200);

		});
	},
	'init': function(){
		this.animate();
	}
};
