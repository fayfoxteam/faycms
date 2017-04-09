<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<div class="inner cf">
    <div class="breadcrumbs">
        <?php
        echo HtmlHelper::link('网站首页', array('')),
            ' &gt; ',
            HtmlHelper::encode($page['title']);
        ?>
    </div>
    <div class="g-sd">
        <div class="cat-list">
            <h3><?php echo HtmlHelper::encode($left_cats['title'])?></h3>
            <ul>
            <?php foreach($left_cats['children'] as $c){
                echo HtmlHelper::link($c['title'], array('cat-'.$c['id']), array(
                    'wrapper'=>'li',
                ));
            }?>
            </ul>
        </div>
    </div>
    <div class="g-mn">
        <h1 class="post-title"><?php echo HtmlHelper::encode($page['title'])?></h1>
        <div class="post-meta">
            <span>发布时间：<?php echo DateHelper::niceShort($page['create_time'])?></span>
            <span>阅读数：<?php echo $page['views']?></span>
        </div>
        <div class="post-content"><?php echo $page['content']?></div>
    </div>
</div>