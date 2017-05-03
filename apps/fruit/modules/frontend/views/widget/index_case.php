<?php
use fay\helpers\HtmlHelper;
?>
<article class="<?php echo $alias?>">
    <div class="inner">
        <figure>
            <?php dump($page)?>
            <?php echo HtmlHelper::img($page['thumbnail'])?>
        </figure>
        <h3><?php echo $page['title']?></h3>
        <div class="item-introtext">
            <?php echo $page['abstract']?>
        </div>
        <?php echo HtmlHelper::link('查看详细', array(
            'page/'.$page['id'],
        ), array(
            'class'=>'more',
        ))?>
    </div>
</article>