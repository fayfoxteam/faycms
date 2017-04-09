<?php
use fay\helpers\HtmlHelper;
?>
<div id="page-item">
    <h2 align="center"><?php echo HtmlHelper::encode($post['title'])?></h2>
    <p><br /></p>
    <?php echo $post['content']?>
</div>