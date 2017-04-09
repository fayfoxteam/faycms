<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
?>
<div class="product-item <?php if($index % 3 == 0)echo 'last'?>">
    <div class="thumbnail-container"><?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>243,
        'dh'=>183,
    )), array('product/'.$data['id']), array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
        'alt'=>HtmlHelper::encode($data['title']),
    ))?></div>
    <h2><?php echo HtmlHelper::link($data['title'], array('product/'.$data['id']))?></h2>
</div>