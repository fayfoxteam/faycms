<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\User;
?>
    <link href="<?= $this->staticFile('css/newslist.css') ?>" rel="stylesheet" type="text/css" />


<div class="gyah-min">
    <?php F::widget()->load('cat_posts') ?>
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
