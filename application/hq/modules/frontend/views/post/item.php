<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\User;
?>
    <link href="<?= $this->staticFile('css/newslist.css') ?>" rel="stylesheet" type="text/css" />


<div class="gyah-min">
    <div class="gyah-minleft">
        <ul>
            <li class="gyah-libg"><a class="gyah-litxt" href="" >公司简介</a> </li>
            <li><a href="">发展历程</a></li>
            <li><a href="">愿景使命</a></li>
            <li><a href="">企业荣誉</a></li>
            <li><a href="">合作伙伴</a></li>
            <li><a href="">联系方式</a> </li></ul>
    </div>
    <div class="gyah-minright">
        <div class="gyah-minrtop">
            <div class="gyah-minrtoptit gyah-minrtoptit2"><?= Html::encode($post['title']) ?></div>
            <div class="info">
                <ul>
                    <li>发布时间: <?= Date::format($post['publish_time']) ?></li>
                    <li>阅读数: <?= $post['views'] ?></li>
                </ul>
            </div>
            <div class="gyah-mt2pictxt">
                <p>
                   <?= $post['content'] ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="clear-30"></div>



<script type="text/javascript">
    $(document).ready(function(){

        $(".suspend").mouseover(function() {
            $(this).stop();
            $(this).animate({width: 140}, 400);
        })

        $(".suspend").mouseout(function() {
            $(this).stop();
            $(this).animate({width: 40}, 400);
        });

    });
</script>
