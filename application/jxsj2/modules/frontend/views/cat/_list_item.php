<?php
use fay\helpers\HtmlHelper;
?>
<li>
    <?php echo HtmlHelper::link($data['title'], array('post/'.$data['id']))?>
    <span class="time"><?php echo date('[Y-m-d]', $data['publish_time'])?></span>
</li>