<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
use fay\helpers\StringHelper;
?>
<article class="cf">
    <?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>250,
        'dh'=>195,
        'alt'=>HtmlHelper::encode($data['title']),
    )), array("{$cat['alias']}-{$data['id']}"), array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
        'class'=>'thumbnail',
    ));?>
    <div class="post-info">
        <h2><?php echo HtmlHelper::link($data['title'], array("{$cat['alias']}-{$data['id']}"))?></h2>
        <time class="publish-time"><?php echo date('Y年m月d日', $data['publish_time'])?></time>
        <div class="abstract"><?php echo StringHelper::nl2p(HtmlHelper::encode($data['abstract']))?></div>
        <?php echo HtmlHelper::link('Read More', array("{$cat['alias']}-{$data['id']}"), array(
            'class'=>'read-more',
        ))?>
    </div>
</article>