<?php
use cms\services\post\PostService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

$post_cats = PostService::service()->getCats($data['id']);
?>
<article class="post-list-item">
    <div class="post-title">
        <h1>
            <a href="<?php echo $this->url('post/'.$data['id'])?>"><?php echo HtmlHelper::encode($data['title'])?></a>
        </h1>
        <span class="post-meta">
            发表于 
            <time><?php echo DateHelper::format($data['publish_time'])?></time>
        </span>
        <div class="clear"></div>
    </div>
    <div class="post-content"><?php echo $data['abstract']?></div>
    <div class="post-tags">
        <?php
        echo HtmlHelper::link('<span>#'.HtmlHelper::encode($data['cat_title']).'</span>', array('cat/'.$data['cat_id']), array(
            'class'=>'post-type',
            'title'=>HtmlHelper::encode($data['cat_title']),
            'encode'=>false,
        ));
        foreach($post_cats as $pc){
            echo HtmlHelper::link('<span>#'.HtmlHelper::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
                'class'=>'post-type',
                'title'=>HtmlHelper::encode($pc['title']),
                'encode'=>false,
            ));
        }
        echo HtmlHelper::link('阅读全文', array('post/'.$data['id']), array(
            'class'=>'post-more-link',
        ));
        ?>
        <div class="clear"></div>
    </div>
</article>