<?php 
use fay\helpers\Html;

?>
<div class="title"><?php echo $data['title']?> Links</div>
<ul class="menu">
    <?php foreach ($links as $link){?>
        <li><?php echo Html::link($link['title'], $link['url'],array(
                'target'=> $link['target'],
            ))?>
        </li>
    <?php } ?>
</ul>