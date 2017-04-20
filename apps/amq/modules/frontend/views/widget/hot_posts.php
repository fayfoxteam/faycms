<?php
/**
 * @var $posts array
 */
?>
<!--热门资讯start-->
<div class="amc-hot">
    <h5 class="newslist-title"><span class="orange-underline"><?php echo \fay\helpers\HtmlHelper::encode($widget->config['title'])?></span></h5>
    
    <ul class="amc-hot-list">
        <?php foreach($posts as $post){?>
        <li>
            <a href="<?php echo $post['post']['link']?>" class="clearfix">
                <?php echo \fay\helpers\HtmlHelper::img($post['post']['thumbnail']['url'], 0, array(
                    'alt'=>\fay\helpers\HtmlHelper::encode($post['post']['title']),
                ))?>
                <div class="amc-hot-text">
                    <p class="amc-hot-title"><?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?></p>
                </div>
            </a>
        </li>
        <?php }?>
    </ul>
</div>
<!--热门资讯over-->