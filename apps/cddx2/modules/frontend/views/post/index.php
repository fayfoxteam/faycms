<?php
use fay\helpers\HtmlHelper;
?>
<div class="inner cf">
    <div class="breadcrumbs">
        <?php
        echo HtmlHelper::link('网站首页', array('')),
            ' &gt; ',
            HtmlHelper::encode($cat['title']);
        ?>
    </div>
    <div class="g-sd">
        <?php F::widget()->load('left-cats')?>
    </div>
    <div class="g-mn">
        <h1 class="sub-title"><?php echo HtmlHelper::encode($cat['title'])?></h1>
        <?php F::widget()->load('post-list')?>
    </div>
</div>