<?php
/**
 * @var $posts array
 */
?>
<?php foreach($posts as $post){?>
<article class="post-list-item">
    <div class="post-title">
        <h1><a href="<?php echo $post['post']['link']?>"><?php
            echo fay\helpers\HtmlHelper::encode($post['post']['title'])
        ?></a></h1>
        <?php if($post['post']['format_publish_time']){?>
        <span class="post-meta">
            发表于 
            <time><?php echo $post['post']['format_publish_time']?></time>
        </span>
        <?php }?>
    </div>
    <div class="post-content"><?php echo nl2br($post['post']['abstract'])?></div>
    <div class="post-tags"><a href="<?php echo $post['post']['link']?>" class="post-more-link">阅读全文</a></div>
</article>
<?php }?>