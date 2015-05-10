/**
 * hq.js
 */

/**
 * 返回顶部
 * Author:peggy
 */
backToTop = function(className){
	var $backToTopTxt = "返回顶部", $backToTopEle = $('<a class="backToTop" href="javascript:void(0);" class="ie6png"></a>').appendTo($(className))
    .text('').attr("title", $backToTopTxt).click(function() {
        $("html, body").animate({ scrollTop: 0 }, 600);
	}), $backToTopFun = function() {
	    var st = $(document).scrollTop(), winh = $(window).height();
	    (st > 0)? $backToTopEle.show(): $backToTopEle.hide();
	    //IE6下的定位
	    if (!window.XMLHttpRequest) {
	        $backToTopEle.css("top", st + winh - 166);
	    }
	};
	$(window).bind("scroll", $backToTopFun);
	$(function() { $backToTopFun(); });
};