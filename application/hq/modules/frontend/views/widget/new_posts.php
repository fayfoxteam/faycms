<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/23
 * Time: 下午8:50
 */
use fay\helpers\Html;
use fay\helpers\String;

?>

<div class="index-minxwzx ">
    <div class="index-mingsjjtit">
        <h3 class="nav-item active" data-id="1">公告</h3>
        <h3 class="nav-item" data-id="2">通知</h3>
            <span class="new-items" id="nav-item-1">
                <a href="<?= $this->url('cat/'. $data['top']) ?>"><img src="<?= $this->staticFile('images') ?>/index_11.png" alt=""/></a>
            </span>
            <span class="new-items hide" id="nav-item-2">
                <a href="<?= $this->url('cat/10019') ?>"><img src="<?= $this->staticFile('images') ?>/index_11.png" alt=""/></a>
            </span>
    </div>

    <div class="clear-10"></div>

    <div class="index-minxwzxmin new-tab new-tab-1" id="gg" >
        <ul>
            <?php foreach ($posts as $p) { ?>
            <li><i></i>
                <?php
                echo Html::link(String::niceShort($p['title'], 20), array(str_replace('{$id}', $p['id'], $data['uri'])), ['title' => $p['title']]);
                if(!empty($data['date_format'])){
                    echo '<span>'.date($data['date_format'], $p['publish_time']).'</span>';
                }

                ?>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="index-minxwzxmin new-tab hide new-tab-2" id="" >
        <?= F::widget()->load('tz_posts') ?>
    </div>
</div>

<script>
    $(function(){
        new Marquee("gg",0,1,345,200,100,0,1000,120,0);

        $('.nav-item').hover(function(){
            var tabId = $(this).attr('data-id');
            $('.nav-item').removeClass('active');
            $(this).addClass('active');

            $('.nav-items').addClass('hide');
            $("#nav-item-" + tabId).removeClass('hide');

            $('.new-tab').addClass('hide');
            $('.new-tab-' + tabId).removeClass('hide');
        });
    });
</script>
