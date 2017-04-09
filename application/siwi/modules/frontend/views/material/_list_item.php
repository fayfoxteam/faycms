<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
use siwi\helpers\FriendlyLink;
?>
<article class="<?php if($index % 4 == 0)echo 'last'?>">
    <?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>283,
        'dh'=>217,
        'alt'=>HtmlHelper::encode($data['title']),
        'title'=>HtmlHelper::encode($data['title']),
        'spare'=>'default',
        'class'=>'thumbnail',
    )), array('material/'.$data['id']) ,array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
    ));?>
    <div class="meta">
        <h3><?php echo HtmlHelper::link($data['title'], array('material/'.$data['id']), array(
            'title'=>HtmlHelper::encode($data['title']),
            'encode'=>false,
        ))?></h3>
        <p class="cat">
            <?php echo HtmlHelper::link($data['parent_cat_title'], FriendlyLink::get('material', $data['parent_cat_id']))?>
            -
            <?php echo HtmlHelper::link($data['cat_title'], FriendlyLink::get('material', $data['parent_cat_id'], $data['cat_id']))?>
        </p>
    </div>
</article>