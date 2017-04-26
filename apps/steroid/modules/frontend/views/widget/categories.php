<?php
use fay\helpers\HtmlHelper;
?>
<div class="widget widget-categories">
    <h5 class="widget-title"><?php echo HtmlHelper::encode($this->config['title'])?></h5>
    <ul>
    <?php foreach($cats as $c){?>
        <li>
            <?php echo HtmlHelper::link($c['title'], $c['link'])?>
        </li>
    <?php }?>
    </ul>
</div>