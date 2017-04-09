<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<div class="page-title">
    <div class="container">
        <h1><?php echo HtmlHelper::encode($keywords)?></h1>
        <div class="breadcrumbs">
            <ol>
                <li><?php echo HtmlHelper::link(OptionService::get('site:sitename'), null)?></li>
                <li><?php echo HtmlHelper::encode($keywords)?></li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="sidebar">
    </div>
    <div class="main-content">
        <div class="post-list"><?php $listview->showData()?></div>
        <?php $listview->showPager()?>
    </div>
</div>