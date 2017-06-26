<?php
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $message array
 */
?>
<header class="page-header">
    <div class="header-content">
        <a href="<?php echo $this->url('zhengyi/user', array(
            'type'=>$message['type'],
            'user_id'=>$message['user_id'],
        ))?>" class="top-return-link">&lt;</a>
        <span class="header-logo"><img src="<?php echo $this->appAssets('images/forum/logo.png')?>"></span>
        <span class="header-title">正义联盟</span>
        <span class="header-subtitle">联合起来行义举</span>
    </div>
</header>
<div class="main-content message-content">
    <h1><?php echo HtmlHelper::encode($message['title'])?></h1>
    <div class="content"><p><?php echo HtmlHelper::encode($message['content'])?></p></div>
    <?php if($message['reply']){?>
        <div class="reply"><strong>关羽军团回复：</strong><?php echo HtmlHelper::encode($message['reply'])?></div>
    <?php }?>
</div>