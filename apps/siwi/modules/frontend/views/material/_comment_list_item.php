<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use cms\services\file\FileService;
?>
<li>
    <div class="avatar">
        <?php echo HtmlHelper::link(HtmlHelper::img($data['avatar'], FileService::PIC_THUMBNAIL, array(
            'alt'=>$data['nickname'],
            'spare'=>'avatar',
        )), array('u/'.$data['user_id']), array(
            'encode'=>false,
            'title'=>false,
        ))?>
    </div>
    <div class="meta">
        <?php echo HtmlHelper::link($data['nickname'], array('u/'.$data['user_id']), array(
            'class'=>'user-link',
        ))?>
        <time class="time"><?php echo DateHelper::niceShort($data['create_time'])?></time>
    </div>
    <div class="comment-content"><?php echo HtmlHelper::encode($data['content'])?></div>
    <?php if($data['parent']){?>
    <div class="parent">
        <em class="arrow-border"></em>
        <em class="arrow"></em>
        <?php echo HtmlHelper::link($data['parent_nickname'], array('u/'.$data['parent_user_id']), array(
            'class'=>'parent-user-link',
        ))?> 说：
        <p class="parent-content"><?php echo HtmlHelper::encode($data['parent_content'])?></p>
    </div>
    <?php }?>
    <?php echo HtmlHelper::link('', 'javascript:;', array(
        'title'=>'回复',
        'class'=>'icon-reply reply-link',
        'data-parent'=>$data['id'],
    ))?>
</li>