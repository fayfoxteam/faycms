<?php
use fay\helpers\HtmlHelper;

/**
 * @var $links array
 * @var $widget \cms\widgets\friendlinks\controllers\IndexController
 */
?>
<div class="widget widget-friendlinks" id="<?php echo HtmlHelper::encode($widget->alias)?>">
    <div class="widget-title">
        <h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
    </div>
    <div class="widget-content">
        <ul>
        <?php foreach($links as $link){?>
            <li><?php echo HtmlHelper::link($link['title'], $link['url'], array(
                'target'=>empty($link['target']) ? false : $link['target'],
            ));?></li>
        <?php }?>
        </ul>
    </div>
</div>