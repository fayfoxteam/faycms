<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
use ncp\helpers\FriendlyLink;
?>
<li>
    <div class="p-img"><?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>280,
        'dh'=>210,
        'alt'=>HtmlHelper::encode($data['title']),
    )), FriendlyLink::getProductLink(array(
        'id'=>$data['id']
    )), array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
    ))?></div>
    <div class="p-name">
        <?php echo HtmlHelper::link($data['title'], FriendlyLink::getProductLink(array(
            'id'=>$data['id']
        )), array(
            'target'=>'_blank',
        ))?>
    </div>
    <div class="p-st">
        <span class="fl"><b>产地：</b><?php echo $data['area']?></span>
        <span class="fr"><b>分类：</b><?php echo $data['cat_title']?></span>
    </div>
</li>