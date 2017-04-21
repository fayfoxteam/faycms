<?php
use fay\helpers\HtmlHelper;

/**
 * @var $post array
 */
?>
<div class="amc-detail">
    <h2><?php echo HtmlHelper::encode($post['post']['title'])?></h2>
    <?php $props = \fay\helpers\ArrayHelper::column($post['props'], null, 'alias')?>
    <div class="come-and-time">
        <?php if(!empty($props['source']['value'])){?>
        来源：<span class="amc-news-come"><?php echo HtmlHelper::encode($props['source']['value'])?></span>
        <?php }?>
        <span class="amc-news-time"><?php echo date('Y-m-d H:i', $post['post']['publish_time'])?></span>
    </div>
    <div class="amc-article"><?php
        echo $post['post']['content']
    ?></div>
    <!-- JiaThis Button BEGIN -->
    <style>
        .amc-share .amc-share-to span{background:none}
    </style>
    <div class="amc-share clearfix jiathis_style_32x32">
        分享：
        <a class="jiathis_button_qzone amc-share-to"></a>
        <a class="jiathis_button_tsina amc-share-to"></a>
        <a class="jiathis_button_weixin amc-share-to"></a>
    </div>
    <script type="text/javascript" >
        var jiathis_config={
            summary:"<?php echo HtmlHelper::encode($post['post']['title'])?>",
            title:"<?php echo HtmlHelper::encode($post['post']['abstract'])?>",
            shortUrl:false,
            hideMore:true
        }
    </script>
    <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
    <!-- JiaThis Button END -->
</div>