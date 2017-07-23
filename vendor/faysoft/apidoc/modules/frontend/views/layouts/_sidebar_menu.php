<?php
use apidoc\helpers\MenuHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $app array
 */
?>
<div class="sidebar-menu <?php if(!F::config()->get('debug'))echo ' fixed';
?>" id="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <div class="dropdown-container open">
                <?php
                    echo HtmlHelper::link($app['name'], array('apidoc/frontend/index/index', array(
                        'app_id'=>$app['id'],
                    )), array(
                        'class'=>'logo',
                    ));
                ?>
                <a href="javascript:" class="switch-apps"><i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="">asdf</a></li>
                    <li><a href="">asdf</a></li>
                    <li><a href="">asdf</a></li>
                </ul>
            </div>
            
            <div class="mobile-menu-toggle">
                <a href="javascript:" class="toggle-mobile-menu">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </header>
        <?php MenuHelper::render(F::app()->_left_menu, isset($current_directory) ? $current_directory : '')?>
    </div>
</div>