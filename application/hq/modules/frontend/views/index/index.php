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
<div class="container">


<div class="index-min">

<?= F::widget()->load('image_posts'); ?>
    <div class="index-minhxyw">
        <div class="index-mingsjjtit"><h3 class="tab" data-id="1" onmouseover="setContentTab(1, 2)">后勤动态</h3> <h3 class="tab" data-id="2" onmouseover="setContentTab(2, 1)">媒体报道</h3>
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
            <li><a href="">保修</a></li>
            <li><a href="">失物招领</a></li>
            <li><a href="">一卡通充值</a></li>
            <li><a href="">水电缴费查询</a></li>
            <li><a href="">网上订餐</a></li>
            <li><a href="">投诉建议</a></li>
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
                    <li><a href=""><img src="<?= $this->staticFile('images/icon_fw.jpg') ?>" />餐饮服务</a></li>
                    <li><a href=""><img src="<?= $this->staticFile('images/icon_fw.jpg') ?>" />餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                    <li><a href="">餐饮服务</a></li>
                </ul>
            </div>
            <div class="content-bottom">总值班电话：88888888（24小时）</div>
        </div>
    </div>

    <div class="center">
        <div class="content-header">快速通道</div>
        <div class="content-box">
            <div class="web web-1"><a href="">办公用品申领平台</a></div>
            <div class="web web-2"><a href="<?= $this->url('tasks/show') ?>">水电使用监控平台</a></div>
            <div class="web web-3"><a href="">阳光厨房监控平台</a></div>
        </div>
    </div>

    <div class="right">
        <div class="hot-topic">
            <div class="content-header">热点专题</div>
            <div class="content-box"></div>
        </div>
    </div>
</div>

</div>
<script>
    $(function(){


        jQuery(".slider_wrap").slide({mainCell:"#slider_box ul",autoPlay:true});
    });

    function setContentTab(show, hide) {
        $('#tab-' + show).removeClass('hide');
        $('#tab-' + hide).addClass('hide');
        $('.tab-' + show).removeClass('hide');
        $('.tab-' + hide).addClass('hide');
    }

</script>