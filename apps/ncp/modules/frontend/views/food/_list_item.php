<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
use ncp\helpers\FriendlyLink;
?>
<li>
    <div class="p-img"><?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>280,
        'dh'=>210,
        'alt'=>HtmlHelper::encode($data['title']),
    )), FriendlyLink::getFoodLink(array(
        'id'=>$data['id']
    )), array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
    ))?></div>
    <div class="p-name">
        <?php echo HtmlHelper::link($data['title'], FriendlyLink::getFoodLink(array(
            'id'=>$data['id']
        )), array(
            'target'=>'_blank',
        ))?>
    </div>
    <div class="p-maoshu"><?php echo HtmlHelper::encode($data['abstract'])?></div>
    <div class="p-st">
        <span class="fl"><?php echo $data['views']?></span>
        <span class="fr"><?php echo HtmlHelper::link('我要吃', FriendlyLink::getFoodLink(array(
            'id'=>$data['id'],
        )), array(
            'class'=>'gowhere',
        ))?></span>
    </div>
</li>