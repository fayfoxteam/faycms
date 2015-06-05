var index = {
	'cover':function(){
		$('.collect-list').on('mouseenter', 'article', function(){
			$(this).find('.cover').slideDown();
			$(this).find('.overlay, .type').fadeIn();
		}).on('mouseleave', 'article', function(){
			$(this).find('.cover').slideUp();
			$(this).find('.overlay, .type').fadeOut();
		});
	},
	'init':function(){
		this.cover();
	}
};