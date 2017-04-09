<?php
use fay\helpers\HtmlHelper;

/**
 * @var $page array
 */
?>
<div class="m-about">
    <h3><?php echo HtmlHelper::encode($page['title'])?></h3>
    <div class="content"><?php echo HtmlHelper::encode($page['abstract'])?></div>
</div>