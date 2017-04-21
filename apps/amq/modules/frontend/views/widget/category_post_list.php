<?php
/**
 * @var $posts array
 */
?>
<!--新闻start-->
<div class="amc-newslist hidden-xs">
    <h5 class="newslist-title">
        <a href="" class="amc-secondnav-title act">域名资讯</a>
        <a href="" class="amc-secondnav-title">域名数据</a>
        <a href="" class="amc-secondnav-title">交易投资</a>
        <a href="" class="amc-secondnav-title">经验交流</a>
        <a href="" class="amc-secondnav-title">域名知识</a>
        <a href="" class="amc-secondnav-title">域名爆料</a>
    </h5>
    <ul class="newslist-contain">
        <?php foreach($posts as $post){?>
        <?php $props = \fay\helpers\ArrayHelper::column($post['props'], null, 'alias')?>
        <li>
            <a href="<?php echo $post['post']['link']?>">
                <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?>">
                <div class="newslist-text">
                    <h5 class="newslist-text-title"><?php
                        echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
                    ?></h5>
                    <p class="newslist-text-article"><?php echo nl2br($post['post']['abstract'])?></p>
                    <div class="newslist-text-tip clearfix">
                        <?php if(!empty($props['source']['value'])){?>
                            <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($props['source']['value'])?></div>
                        <?php }?>
                        <?php if($post['post']['format_publish_time']){?>
                            <div class="newslist-text-time"><?php echo $post['post']['format_publish_time']?></div>
                        <?php }?>
                    </div>
                </div>
            </a>
        </li>
        <?php }?>
    </ul>
    <div class="loadmore">加载更多...</div>
</div>
<!--新闻start-->