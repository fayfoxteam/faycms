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
        <span class="header-title">兵谏</span>
        <span class="header-subtitle">敢于兵谏乃真勇士！</span>
    </div>
</header>
<nav>
    <ul class="bingjian-nav">
        <li><?php echo HtmlHelper::link('攻城', array('bingjian', array(
                'type'=>GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG,
            )), array(
                'class'=>$type == GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG ? 'crt' : '',
            ))?></li>
        <li><?php echo HtmlHelper::link('守城', array('bingjian', array(
                'type'=>GuangongMessagesTable::TYPE_BINGJIAN_SHOUCHENG,
            )), array(
                'class'=>$type == GuangongMessagesTable::TYPE_BINGJIAN_SHOUCHENG ? 'crt' : '',
            ))?></li>
        <li><?php echo HtmlHelper::link('兵器', array('bingjian', array(
                'type'=>GuangongMessagesTable::TYPE_BINGJIAN_BINGQI,
            )), array(
                'class'=>$type == GuangongMessagesTable::TYPE_BINGJIAN_BINGQI ? 'crt' : '',
            ))?></li>
        <li><?php echo HtmlHelper::link('服饰', array('bingjian', array(
                'type'=>GuangongMessagesTable::TYPE_BINGJIAN_FUSHI,
            )), array(
                'class'=>$type == GuangongMessagesTable::TYPE_BINGJIAN_FUSHI ? 'crt' : '',
            ))?></li>
        <li><?php echo HtmlHelper::link('我建言', array('bingjian/jianyan', array(
                'type'=>GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG,
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