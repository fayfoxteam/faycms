<?php
use fay\helpers\Html;
?>

<li>> 
    <span><?php echo date('Y-m-d',$data['publish_time'])?></span>
    <?php echo Html::link($data['title'], array('post/'.$data['id']))?>
</li>