<div id="header">
    <div class="header">
        <div class="hesder-top">
            <a href="">加入收藏</a>│<span><img src="<?= $img_url ?>/index_03.png" alt=""/></span>服务热线：400-800-800
        </div>
        <div class="Logo">
            <div class="Logo-img"><img src="<?= $img_url ?>/ypcol.gif" width="100%"/></div>
            <div class="Logo-min">
                <div class="Logo-mintit">后勤部门</div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="nav-menu">
    <div class="navBar">
        <ul class="nav clearfix">
            <li class="m">
                <h3><a href="http://www.sucai888.com/">网站首页</a></h3>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">关于我们</a></h3>
                <ul class="sub">
                    <li><a href="http://www.sucai888.com/">企业简介</a></li>
                    <li><a href="http://www.sucai888.com/">组织架构</a></li>
                    <li><a href="http://www.sucai888.com/">企业资质</a></li>
                    <li><a href="http://www.sucai888.com/">企业文化</a></li>
                    <li><a href="http://www.sucai888.com/">企业文化</a></li>
                    <li><a href="http://www.sucai888.com/">企业文化</a></li>
                    <li><a href="http://www.sucai888.com/">企业文化</a></li>
                </ul>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">资质认定</a></h3>
                <ul class="sub">
                    <li><a href="http://www.sucai888.com/">国家高新认证</a></li>
                    <li><a href="http://www.sucai888.com/">软件企业认证</a></li>
                    <li><a href="http://www.sucai888.com/">深圳市高企认证</a></li>
                    <li><a href="http://www.sucai888.com/">其它认证</a></li>
                </ul>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">政府扶持</a></h3>
                <ul class="sub" style="display: none;">
                    <li><a href="http://www.sucai888.com/">深圳市级扶持</a></li>
                    <li><a href="http://www.sucai888.com/">各区级扶持</a></li>
                    <li><a href="http://www.sucai888.com/">广东省级扶持</a></li>
                    <li><a href="http://www.sucai888.com/">国家和部级扶持</a></li>
                </ul>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">知识产权</a></h3>
                <ul class="sub" style="display: none;">
                    <li><a href="http://www.sucai888.com/">知识产权申请</a></li>
                    <li><a href="http://www.sucai888.com/">知识产权转让</a></li>
                    <li><a href="http://www.sucai888.com/">技术咨询</a></li>
                    <li><a href="http://www.sucai888.com/">技术成果鉴定</a></li>
                </ul>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">上市服务</a></h3>
                <ul class="sub" style="display: none;">
                    <li><a href="http://www.sucai888.com/">上市条件及流程</a></li>
                    <li><a href="http://www.sucai888.com/">上市顾问</a></li>
                    <li><a href="http://www.sucai888.com/">税收筹划</a></li>
                    <li><a href="http://www.sucai888.com/">资产重组</a></li>
                </ul>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">人才招聘</a></h3>
            </li>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://www.sucai888.com/">联系我们</a></h3>
            </li>
            <li class="block" style="left:251px;"></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var nav = $(".nav");
        var init = $(".nav .m").eq(ind);
        var block = $(".nav .block");
        block.css({
            "left": init.position().left - 3
        });
        nav.hover(function() {},
            function() {
                block.stop().animate({
                        "left": init.position().left - 3
                    },
                    100);
            });
        $(".nav").slide({
            type: "menu",
            titCell: ".m",
            targetCell: ".sub",
            delayTime: 300,
            triggerTime: 0,
            returnDefault: true,
            defaultIndex: ind,
            startFun: function(i, c, s, tit) {
                block.stop().animate({
                        "left": tit.eq(i).position().left - 3
                    },
                    100);
            }
        });
    });

    var ind = 0;
</script>