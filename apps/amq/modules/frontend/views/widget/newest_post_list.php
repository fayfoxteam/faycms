<?php
/**
 * @var $posts array
 */
?>
<!--新闻start-->
<div class="amc-newslist hidden-xs">
    <h5 class="newslist-title hidden-xs"><span class="orange-underline">最新消息</span></h5>
    <ul class="newslist-contain">
    <?php foreach($posts as $post){?>
        <li>
            <a href="<?php echo $post['post']['link']?>">
                <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?>">
                <div class="newslist-text">
                    <h5 class="newslist-text-title"><?php
                        echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
                    ?></h5>
                    <p class="newslist-text-article"><?php echo nl2br($post['post']['abstract'])?></p>
                    <div class="newslist-text-tip clearfix">
                        <?php if(!empty($post['extra']['source'])){?>
                            <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($post['extra']['source'])?></div>
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
