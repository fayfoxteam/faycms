<?php
use cms\services\MenuService;
use fay\helpers\HtmlHelper;

$menu = MenuService::service()->getTree('_top');
?>
<nav id="header-menu" class="clearfix">
    <div class="w1000">
        <ul>
        <?php $first_menu = array_shift($menu)?>
        <li><?php echo HtmlHelper::link($first_menu['title'], $first_menu['link'], array(
            'class'=>(empty($current_header_menu) || $current_header_menu == 'home') ? 'current' : '',
            'encode'=>false,
            'title'=>'首页',
        ))?></li>
        <?php foreach($menu as $m){?>
            <li><?php echo HtmlHelper::link($m['title'], $m['link'], array(
                'class'=>(isset($current_header_menu) && $current_header_menu == $m['alias']) ? 'current' : '',
                'encode'=>false,
            ))?></li>
        <?php }?>
        </ul>
    </div>
</nav>