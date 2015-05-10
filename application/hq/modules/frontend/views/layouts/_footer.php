<?php

?>
<!-- /container -->
<div class="container">

    <div class="hr"></div>

</div>

<!-- /container -->


</div>


<div class="footer">

    <!--<p class="fc999 b-5 footer-info">致力于前沿生物科技和成功商业模式的传播</p>-->
    <div class="clearfix" style="background:#EDEDED;">

        <!--<p class="fc999 pull-left footer-info">Copyright © 2013 生物探索网站<span class="l-20">苏ICP备11025281号</span></p>-->

        <ul class="about-ul">
            <li>客服电话 400-100-8884</li>
            <li><span>|</span></li>
            <li><a title="广告投放" href="javascript:;" class="fc666" target="_blank">广告投放</a></li>
            <li><span>|</span></li>
            <li><a title="企业服务" href="javascript:;" class="fc666" target="_blank">企业服务</a></li>
            <li><span>|</span></li>
            <li><a title="公司博客" href="javascript:;" class="fc666" target="_blank" >公司博客</a></li>
            <li><span>|</span></li>
            <li><a title="加入我们" href="javascript:;" class="fc666" target="_blank">加入我们</a></li>
            <li><span>|</span></li>
            <li><a title="服务协议" href="javascript:;" class="fc666" target="_blank">服务协议</a></li>
            <li><span>|</span></li>
            <!-- <li id="zk_btn" class="ie6png down-class">
                <a title="友情链接" href="javascript:void(0);" class="fc666">友情链接</a>
            </li>-->
            <li id="zk_btn" class="ie6png down-class">
                <a title="友情链接" href="javascript:void(0);" class="fc666">友情链接</a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>

    <div class="friend-link border-all t-20 b-20">
        <ul class="clearfix">
            
        </ul>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            var flag = 0;
            $(".friend-link").hide();
            //Down
            $("#zk_btn").click(function(){
                if(flag == 0){
                    $(".friend-link").slideDown(400);
                    $(this).removeClass('down-class');
                    $(this).addClass('up-class');
                    $("html,body").animate({scrollTop:($(".friend-link").offset().top+2000)},600);
                    flag = 1;
                }else{
                    $(".friend-link").slideUp(400);
                    $(this).removeClass('up-class');
                    $(this).addClass('down-class');
                    flag = 0;
                }
            });
        });
    </script>

    <p>Copyright ©2013　　备8888888888号</p>

</div>


<script type="text/javascript">
    //回到顶部
    backToTop('body');

</script>
