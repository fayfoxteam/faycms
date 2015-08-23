/**
 * 下拉后固定到顶部
 */
jQuery.fn.extend({
	fixcontent: function(options){
		$(this).each(function(){
			var o = this;
			var timeout = null;
			var offset_top = $(o).offset().top;
			$(o).css({
				"width": $(o).width()
			});
			$(window).scroll(function(){
				clearTimeout(timeout);
				if($("body").get(0).getBoundingClientRect().top <= -(offset_top + 10)){
					if(!$(o).next(".fix-content-succedaneum").length){
						$(o).after('<div class="fix-content-succedaneum" style="width:'+$(o).width()+'px;height:'+$(o).height()+'px;"></div>');
					}
					if($.browser.msie && $.browser.version < 8){
						timeout = setTimeout(function(){
							$(o).css({
								"position": "absolute",
								"z-index": 1000,
								"left": $(o).offset().left
							})
							.animate({
								"top": - $("body").get(0).getBoundingClientRect().top
							})
							.addClass("col-fixed");
						}, 200);
					}else{
						$(o).css({
							"position": "fixed",
							"top": 0,
							"z-index": 1000,
							"left": $(o).offset().left
						})
						.addClass("col-fixed");
					}
				}else{
					$(o).css({
						"position": "relative",
						"top": 0,
						"left": 0
					})
					.removeClass("col-fixed");
					$(o).next(".fix-content-succedaneum").remove();
				}
			});
		});
	}
});