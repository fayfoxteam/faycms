<?php
use fay\helpers\HtmlHelper;
use guangong\models\tables\GuangongMessagesTable;

/**
 * @var $this \fay\core\View
 * @var $listview \fay\common\ListView
 * @var $type int
 */
?>
<header class="page-header">
    <div class="header-content">
        <span class="header-logo"><img src="<?php echo $this->appAssets('images/forum/logo.png')?>"></span>
        <span class="header-title">正义联盟</span>
        <span class="header-subtitle">联合起来行义举</span>
    </div>
</header>
<nav>
    <ul class="zhengyi-nav">
        <li><?php echo HtmlHelper::link('自行义举', array('zhengyi', array(
                'type'=>GuangongMessagesTable::TYPE_ZHENGYILIANMENG_ZIXINGSHANJU,
            )), array(
                'class'=>$type == GuangongMessagesTable::TYPE_ZHENGYILIANMENG_ZIXINGSHANJU ? 'crt' : '',
            ))?></li>
        <li><?php echo HtmlHelper::link('我上传', array('zhengyi/jianyan', array(
                'type'=>GuangongMessagesTable::TYPE_ZHENGYILIANMENG_ZIXINGSHANJU,
            )), array(
                'class'=>'fc-grey',
            ))?></li>
    </ul>
</nav>
<div class="main-content">
    <div class="messages2">
        <?php $listview->showData()?>
    </div>
    <div class="message-pager">
        <?php $listview->showPager()?>
    </div>
</div>