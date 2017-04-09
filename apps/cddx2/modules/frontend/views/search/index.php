<?php
use fay\helpers\HtmlHelper;
?>
<div class="inner cf">
    <div class="breadcrumbs">
        <?php
        echo HtmlHelper::link('网站首页', array('')),
            ' &gt; 全站搜索';
        ?>
    </div>
    <div class="g-sd">
        <?php F::widget()->load('left-cats')?>
    </div>
    <div class="g-mn">
        <h1 class="sub-title">搜索关键词：<?php echo HtmlHelper::encode($keywords)?></h1>
        <ul class="inner-post-list"><?php $listview->showData()?></ul>
        <?php $listview->showPager()?>
    </div>
</div>