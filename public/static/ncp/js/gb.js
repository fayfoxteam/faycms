// JavaScript Document

//搜索里面内容
 function inputval(v){
	$(".J_text_val").focus(function(){
			$(this).val("");
		})
	$(".J_text_val").blur(function(){
			if($(this).val().length>0){
 				}
				else{
					$(this).val(v)
 				}		   
		})
 } 
  // 切换
		$(".J_tabs a").bind("click", function () {
		 var index = $(this).index(index);
		 var divs = $(".J_tab_infos > div");
			$(this).parent().children("a").attr("class", "no");//灏嗘墍鏈夐€夐」缃负鏈€変腑
			$(this).attr("class", "cur");     //璁剧疆褰撳墠閫変腑椤逛负閫変腑鏍峰紡
			divs.hide(); 
			divs.eq(index).show();
			return false
  });
 	$(".J_tab_infos > div").css({"display":"none"})
	$(".J_tab_infos > div:first").css({"display":"block"})
 	
	$(".m_tabs a").bind("click", function () {
			 var index = $(this).index(index);
			 var divs = $(".m_tabs_info > div");
				$(this).parent().children("a").attr("class", "no");//灏嗘墍鏈夐€夐」缃负鏈€変腑
				$(this).attr("class", "cur");     //璁剧疆褰撳墠閫変腑椤逛负閫変腑鏍峰紡
				divs.hide(); 
				divs.eq(index).show();
				return false
  });
 	$(".m_tabs_info > div").css({"display":"none"})
	$(".m_tabs_info > div:first").css({"display":"block"})
	
	
$("#mune,.mune_info ul li").hover(function(){
 	 $(this).addClass("on").siblings().removeClass("on");
	});

var mh= $(".mune_info").height();
$(".mune_info .mune_list").css({"height":mh-2})
 