jQuery.fn.extend({
	menu: function(){
		$(this).children("li").each(function(key){
			$(this).prepend('<span class="menu-l pngbg"></span>')
				.append('<span class="menu-r pngbg"></span>')
				.children("a").wrap('<span class="menu-m pngbg"></span>')
		})
	}
})