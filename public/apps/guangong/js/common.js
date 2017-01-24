var common = {
	'swiper': function(){
		if($('.swiper-container').length){
			system.getCss(system.assets('js/swiper/css/swiper.min.css'));
			system.getScript(system.assets('js/swiper/js/swiper.jquery.min.js'), function(){
				var swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
					//grabCursor: true,
					//loop: true,
					nextButton: '.swiper-btn-next',
					prevButton: '.swiper-btn-prev'
				});
			});
		}
	},
	'init': function(){
		this.swiper();
	}
};
