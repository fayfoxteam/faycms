<?php
/**
 * @var $posts array
 */

use cms\services\file\FileService;

$first_slice = array_slice($posts, 0, 3);
?>

<div id="mobile-newslist-container">
<?php if($first_slice){?>
<!--新闻列表start-->
<ul class="m-amc-newslist">
<?php foreach($first_slice as $post){?>
    <li>
        <a href="<?php echo $post['post']['link']?>" class="clearfix">
            <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?>" class="newspic">
            <div class="m-newslist-title"><?php
                echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
            ?></div>
            <div class="newslist-text-tip clearfix">
                <?php if(!empty($post['extra']['source'])){?>
                    <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($post['extra']['source'])?></div>
                <?php }?>
                <?php if($post['post']['format_publish_time']){?>
                    <div class="newslist-text-time"><?php echo $post['post']['format_publish_time']?></div>
                <?php }?>
            </div>
        </a>
    </li>
<?php }?>
</ul>
<!--新闻列表over-->

<?php
    //从domains widget获取数据，因为运营肯定懒得维护两份广告
    $domains = F::widget()->getData('domains');
    if(isset($domains['data'][\F::input()->get('page', 'intval', 1) - 1])){
        $domain_info = explode('|', $domains['data'][\F::input()->get('page', 'intval', 1) - 1]['key']);
        $domain_link = $domains['data'][\F::input()->get('page', 'intval', 1) - 1]['value'];
?>
        <!--推荐域名start-->
        <div class="m-amc-recommend">
            <div class="m-amc-recommendpic">域名推荐</div>
            <div class="m-amc-recommendmain">
                <div class="col-xs-4 m-amc-yuming"><?php echo \fay\helpers\HtmlHelper::encode($domain_info[0])?></div>
                <div class="col-xs-4 m-amc-price"><?php
                    if(empty($domain_info[1])){
                        echo '点击查看';
                    }else{
                        echo '￥', $domain_info[1];
                    }?></div>
                <div class="col-xs-4 m-amc-look"><a href="<?php echo \fay\helpers\HtmlHelper::encode($domain_link)?>" target="_blank">查看</a></div>
            </div>
        </div>
        <!--推荐域名over-->
    <?php }?>
<?php }?>

<?php
$second_slice = array_slice($posts, 3, 3);
if($second_slice){?>
<!--新闻列表start-->
<ul class="m-amc-newslist">
<?php foreach($second_slice as $post){?>
    <li>
        <a href="<?php echo $post['post']['link']?>" class="clearfix">
            <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?>" class="newspic">
            <div class="m-newslist-title"><?php
                echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
                ?></div>
            <div class="newslist-text-tip clearfix">
                <?php if(!empty($post['extra']['source'])){?>
                    <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($post['extra']['source'])?></div>
                <?php }?>
                <?php if($post['post']['format_publish_time']){?>
                    <div class="newslist-text-time"><?php echo $post['post']['format_publish_time']?></div>
                <?php }?>
            </div>
        </a>
    </li>
<?php }?>
</ul>
<!--新闻列表over-->
    
<?php
    //从pc端侧边栏获取数据，但是模版有差异，不能直接调用widget
    $index_sidebar_slider_ad_data = F::widget()->getData('index-sidebar-slider-ad');
?>
<div class="m-amc-ad">
    <div id="carousel-example-generic3" class="carousel slide amc-ad" data-ride="carousel">
        <ol class="carousel-indicators amc-circle3">
        <?php foreach($index_sidebar_slider_ad_data['files'] as $key => $file){?>
            <li data-target="#carousel-example-generic3" data-slide-to="<?php echo $key?>" <?php if(!$key) echo 'class="active"'?>></li>
        <?php }?>
        </ol>

        <div class="carousel-inner" role="listbox">
            <?php foreach($index_sidebar_slider_ad_data['files'] as $key => $file){?>
                <?php $file_url = FileService::getUrl($file['file_id'], FileService::PIC_RESIZE, array(
                    'dw'=>414,
                    'dh'=>205,
                ))?>
                <div class="item <?php if(!$key)echo 'active'?>">
                    <a href="<?php echo $file['link']?>"><img src="<?php echo $file_url?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($file['title'])?>"></a>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<?php }?>

<?php
$third_slice = array_slice($posts, 6);
if($third_slice){?>
<!--新闻列表start-->
<ul class="m-amc-newslist">
<?php foreach($third_slice as $post){?>
    <li>
        <a href="<?php echo $post['post']['link']?>" class="clearfix">
            <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?>" class="newspic">
            <div class="m-newslist-title"><?php
                echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
                ?></div>
            <div class="newslist-text-tip clearfix">
                <?php if(!empty($post['extra']['source'])){?>
                    <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($post['extra']['source'])?></div>
                <?php }?>
                <?php if($post['post']['format_publish_time']){?>
                    <div class="newslist-text-time"><?php echo $post['post']['format_publish_time']?></div>
                <?php }?>
            </div>
        </a>
    </li>
<?php }?>
</ul>
<!--新闻列表over-->
<?php }?>
</div>
<ul class="m-amc-newslist">
    <li class="m-loadmore"><a href="javascript:">加载更多...</a></li>
</ul>
