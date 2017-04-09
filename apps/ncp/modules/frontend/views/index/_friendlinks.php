<?php
use fay\helpers\HtmlHelper;
?>
<div class="link mt40">
    <div class="link_info">
    <?php foreach($links as $l){?>
        <?php echo HtmlHelper::link($l['title'], $l['url'], array(
            'target'=>$l['target'],
        ));?>
    <?php }?>
    </div>
</div>