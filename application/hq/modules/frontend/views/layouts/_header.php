<?php
use fay\models\Category;
use fay\helpers\Html;
?>
<div id="header">
    <div class="header">
        <div class="hesder-top">
            <a href="javascript:;" onclick="AddFavorite('<?= $this->url() ?>', '绍兴文理学院元培学院后勤管理处')";>加入收藏</a>│<span><img src="<?= $img_url ?>/index_03.png" alt=""/></span>服务热线：400-800-800
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
                <h3><a href="<?= $this->url() ?>">网站首页</a></h3>
            </li>
            <li class="s">|</li>
            <?php
            //文章分类
            $cats = Category::model()->getTree('_system_post');
            $length = count($cats);
            foreach ($cats as $k => $cat)
            {
                if (!$cat['is_nav']) continue;
                echo '<li class="m">';
                echo '<h3>', Html::link($cat['title'], ['cat/'.$cat['id']], ['title' => false]), '</h3>';

                if (!empty($cat['children']))
                {
                    echo '<ul class="sub">';
                            foreach ($cat['children'] as $c)
                            {
                                if(!$c['is_nav'])continue;
                                echo '<li>', Html::link($c['title'], ['cat/'.$c['id']], ['title'=> false]), '</li>';
                            }
                    echo '</ul>';
                }
                if ($k < $length -1)
                {
                    echo '<li class="s">|</li>';
                }

            }
            echo '</li>';
            ?>
            <li class="s">|</li>
            <li class="m">
                <h3><a href="http://ypc.edu.cn" target="_blank">学院主页</a></h3>
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