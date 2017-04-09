<?php
use fay\helpers\HtmlHelper;
?>
<aside class="col-md-5 col-sm-6">
    <h4><?php echo HtmlHelper::encode($page['title'])?></h4>
    <div>
        <p><?php echo HtmlHelper::encode($page['abstract'])?></p>
    </div>
</aside>