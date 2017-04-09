<?php
use fay\helpers\HtmlHelper;

\F::app()->layout->current_header_menu = $page['alias'];
?>
<div class="page-title">
    <h1><?php echo HtmlHelper::encode($page['title'])?></h1>
</div>
<div class="page-content cf"><?php echo $page['content']?></div>