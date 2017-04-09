<?php
use fay\helpers\HtmlHelper;

$crt_cat = F::input()->get('cat');
?>
<ul>
    <li><?php echo HtmlHelper::link('全部', array(
        'product'
    ), array(
        'class'=>($crt_cat == '') ? 'crt' : false,
    ))?></li>
    <?php foreach($cats as $c){?>
        <li><?php echo HtmlHelper::link($c['title'], array(
            'product/'.$c['alias']
        ), array(
            'class'=>($crt_cat == $c['alias']) ? 'crt' : false,
        ))?></li>
    <?php }?>
</ul>