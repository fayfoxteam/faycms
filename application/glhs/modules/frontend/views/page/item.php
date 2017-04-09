<?php
use fay\helpers\HtmlHelper;
use fay\services\OptionService;
?>
<div class="page-title">
    <div class="container">
        <h1><?php echo HtmlHelper::encode($page['title'])?></h1>
        <div class="breadcrumbs">
            <ol>
                <li><?php echo HtmlHelper::link(OptionService::get('site:sitename'), null)?></li>
                <li>关于我们</li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="g-mn">
        <h1 class="sec-title"><span><?php echo HtmlHelper::encode($page['title'])?></span></h1>
        
        <div id="contact-page" class="clearfix">
            <?php echo $page['content']?>
        </div>
    </div>
</div>