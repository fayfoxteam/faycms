
$(function(){
            if($("#carousel-example-generic").length>0){
                          //轮播
                          $('#carousel-example-generic').carousel({
                                       interval: 8000
                          })

                          $('#carousel-example-generic2').carousel({
                                       interval: 8000
                          })

                          $('#carousel-example-generic3').carousel({
                                       interval: 8000
                          })

                          //轮播手势滑动
                          $(function(){
                                       var myElement= document.getElementById('carousel-example-generic');
                                       var hm=new Hammer(myElement);
                                       hm.on("swipeleft",function(){
                                                  $('#carousel-example-generic').carousel('next');
                                       })
                                       hm.on("swiperight",function(){
                                                  $('#carousel-example-generic').carousel('prev');
                                       })


                                       var myElement3= document.getElementById('carousel-example-generic3');
                                       var hm3=new Hammer(myElement3);
                                       hm3.on("swipeleft",function(){
                                                  $('#carousel-example-generic3').carousel('next');
                                       })
                                       hm3.on("swiperight",function(){
                                                  $('#carousel-example-generic3').carousel('prev');
                                       })
                          })
            }
            
})







$(function(){
             if($("#gallery").length>0){
                         //移动端的滑动二级导航
                         $('#gallery').touchSlider({
                                       mode: 'shift',
                                       offset: 'auto'
                         })
            }
})







$(function(){
            //返回顶部
            $(".retop").click(function() {
                    $("html,body").animate({scrollTop:0}, 500);
            });


            //移动端搜索侧拉
            $(".m-search").click(function(){
                $(".m-nav-div").stop().animate({width:"0"},400);   
                $("body").addClass("OH");
                $(".m-search-div").stop().animate({width:"100%"},400);   
            })
            $(".amc-search-close").click(function(){               //关闭搜索
                $(".m-search-div").stop().animate({width:"0"},400);
            })
            $(".m-amc-hotword").click(function(){
                var word=$(this).text();
                $(".m-searchfor").val(word);
            })


            //移动端菜单导航侧拉
            $(".m-nav").click(function(){
                $(".m-search-div").stop().animate({width:"0"},400);
                if($(".m-nav-div").width()==0){
                    $("body").addClass("OH");
                    $(".m-nav-div").stop().animate({width:"100%"},400);
                }else{
                    $("body").removeClass("OH");
                    $(".m-nav-div").stop().animate({width:"0"},400);
                }
            })

           
})

$(function(){
            //侧栏fix
            $(".amc-forum li:last-child").addClass("hotbt")
            var H=$(".hotbt").offset().top+60;
             var h=$(".hot-A").height();
            $(window).scroll(function(){
                      if(H<$(this).scrollTop()){
                              $(".hot-A").addClass("fix")
                              $("#carousel-example-generic2").addClass("fix").css("top",h-50);
                      }else{
                              $(".hot-A").removeClass("fix")
                              $("#carousel-example-generic2").removeClass("fix");
                      }
            })
})


