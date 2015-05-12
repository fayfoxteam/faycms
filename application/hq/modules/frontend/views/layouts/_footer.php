<?php
use fay\models\Option;
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
            <li>咨询电话 0575-88888888</li>
            <li><span>|</span></li>
            <li><a title="1111" href="javascript:;" class="fc666" target="_blank">1111</a></li>
            <li><span>|</span></li>
            <li><a title="2222" href="javascript:;" class="fc666" target="_blank">2222</a></li>
            <li><span>|</span></li>
            <li><a title="3333" href="javascript:;" class="fc666" target="_blank" >3333</a></li>
            <li><span>|</span></li>
            <li id="zk_btn" class="ie6png down-class">
                <a title="友情链接" href="javascript:void(0);" class="fc666">友情链接</a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>

    <div class="friend-link border-all t-20 b-20">
            <?php F::app()->widget->load('friendLinks'); ?>
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

    <p><?= Option::get('copyright');  ?></p>

</div>


<script type="text/javascript">
    //回到顶部
    backToTop('body');

</script>
