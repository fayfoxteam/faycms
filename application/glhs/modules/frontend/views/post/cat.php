<?php
use fay\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<div class="page-title">
    <div class="container">
        <h1><?php echo HtmlHelper::encode($cat['title'])?></h1>
        <div class="breadcrumbs">
            <ol>
                <li><?php echo HtmlHelper::link(OptionService::get('site:sitename'), null)?></li>
                <li><?php echo HtmlHelper::encode($cat['title'])?></li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="sidebar">
        <?php F::widget()->render('fay/categories', array(
            'top'=>$cat['id'],
            'uri'=>'{$alias}',
            'template'=>'frontend/widget/categories',
        ))?>
    </div>
    <div class="main-content">
        <div class="post-list"><?php $listview->showData()?></div>
        <?php $listview->showPager()?>
    </div>
</div>