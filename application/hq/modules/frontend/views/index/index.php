<?php 
$img_url = $this->staticFile('images');
?>
<div class="banner">
    <div id="indexbanner" class="indexbanner">

        <div class="bd">
            <ul>
                <li><a href="" target="_blank"><img src="<?= $img_url ?>/wl.jpg" /></a></li>
                
            </ul>
        </div>
    </div>
</div>
<div class="bg">


<div class="container">


<div class="index-min">

<?= F::widget()->load('image_posts'); ?>
    <div class="index-minhxyw">
        <div class="index-mingsjjtit">
            <h3 class="tab active" data-id="1" onmouseover="setContentTab(1, 2)">后勤动态</h3>
            <h3 class="tab" data-id="2" onmouseover="setContentTab(2, 1)">媒体报道</h3>
            <span class="tab-1"><a href="11"><img src="<?= $img_url ?>/index_11.png" alt=""/></a></span>
            <span class="tab-2 hide"><a href="22"><img src="<?= $img_url ?>/index_11.png" alt=""/></a></span>
        </div>
        <div class="index-minhxywmin" id="tab-1">
            <?= F::widget()->load('hqdt_posts'); ?>
        </div>
        <div class="index-minhxywmin hide" id="tab-2">
            <?= F::widget()->load('mtbd_posts'); ?>
        </div>
    </div>
        <?= F::widget()->load('new_posts') ?>
</div>

<div id="serve">
    <div class="title">服务大厅</div>
    <div class="serve-list">
        <ul>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic1.jpg') ?>" />保修</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic2.jpg') ?>" />失物招领</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic3.jpg') ?>" />一卡通充值</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic4.jpg') ?>" />水电缴费查询</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic5.jpg') ?>" />网上订餐</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic6.jpg') ?>" />投诉建议</a></li>
            <li><a href=""><img src="<?= $this->staticFile('images/box2_pic7.jpg') ?>" />投诉建议</a></li>
        </ul>
    </div>
</div>
<div class="clear-20"></div>
<div id="content-bottom">
    <div class="left">
        <div class="guide">
            <div class="content-header">服务指南</div>
            <div class="content-box">
                <ul>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_01.gif') ?>" />餐饮服务</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_02.gif') ?>" />交通服务</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_03.gif') ?>" />水电维修</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_04.gif') ?>" />邮政通信</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_05.gif') ?>" />超市便利</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_06.gif') ?>" />教材服务</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_07.gif') ?>" />常见问题</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon/icon_08.gif') ?>" />值班电话</a></li>
                </ul>
            </div>
            <div class="content-bottom">总值班电话：88888888（24小时）</div>
        </div>
    </div>

    <div class="center">
       <?= F::widget()->load('index_fast') ?>
    </div>

    <div class="right">
        <?= F::widget()->load('hot_images'); ?>
    </div>
</div>

</div>
<script>
    $(function(){
        jQuery(".slider_wrap").slide({mainCell:"#slider_box ul",autoPlay:true});

        //主页tab切换
        $('.tab').hover(function(){
            $('.tab').removeClass('active');
            $(this).addClass('active');
        });
    });

    function setContentTab(show, hide) {
        $('#tab-' + show).removeClass('hide');
        $('#tab-' + hide).addClass('hide');
        $('.tab-' + show).removeClass('hide');
        $('.tab-' + hide).addClass('hide');
    }

</script>