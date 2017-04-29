<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<div class="mobile-nav" id="mobile-nav">
    <div class="mobile-bar-container">
        <a href="javascript:;">
            <span class="icons">
                <i></i>
                <i></i>
                <i></i>
            </span>
        </a>
    </div>
    <div class="mobile-nav-mask"></div>
    <nav class="navigator">
        <?php F::widget()->load('nav-left');?>
        <?php F::widget()->load('nav-right');?>
        <ul>
            <li class="inquiry-link"><a href="#section-contact">INQUIRY NOW</a></li>
        </ul>
    </nav>
</div>