<?php
use fay\helpers\Html;
?>

<div class="index-bottommyq">
    友情链接：
    <?php
     foreach ($links as $key => $link)
     {
         echo Html::link($link['title'], $link['url'], ['target' => $link['target']]);
     }
    ?>
</div>