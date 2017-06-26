<?php
/**
 * @var $this \fay\core\View
 * @var $listview \fay\common\ListView
 */
?>
<header class="page-header">
    <div class="header-content">
        <a href="<?php echo $this->url('zhengyi', array(
            'type'=>$type
        ))?>" class="top-return-link">&lt;</a>
        <span class="header-logo"><img src="<?php echo $this->appAssets('images/forum/logo.png')?>"></span>
        <span class="header-title">正义联盟</span>
        <span class="header-subtitle">联合起来行义举</span>
    </div>
</header>
<div class="main-content">
    <div class="messages">
        <?php $listview->showData()?>
    </div>
    <div class="message-pager">
        <?php $listview->showPager()?>
    </div>
</div>