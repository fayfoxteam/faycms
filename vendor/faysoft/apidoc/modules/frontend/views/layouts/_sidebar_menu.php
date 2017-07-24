<?php
use apidoc\helpers\MenuHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $current_app array 当前APP
 * @var $apps array 所有APP
 */
?>
<div class="sidebar-menu <?php if(!F::config()->get('debug'))echo ' fixed';
?>" id="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <?php
                echo HtmlHelper::link($current_app['name'], array('apidoc/frontend/index/index', array(
                    'app_id'=>$current_app['id'],
                )), array(
                    'class'=>'logo',
                ));
            ?>
            <?php if(isset($apps[1])){?>
                <div class="dropdown-container">
                    <a href="javascript:" class="switch-apps" title="切换应用"><i class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                    <?php foreach($apps as $app){?>
                        <li><?php echo HtmlHelper::link($app['name'], array('apidoc/frontend/index/index', array(
                            'app_id'=>$app['id'],
                        )), array(
                            'class'=>$app['id'] == $current_app['id'] ? 'crt' : ''
                        ))?></li>
                    <?php }?>
                    </ul>
                </div>
            <?php }?>
            
            <div class="mobile-menu-toggle">
                <a href="javascript:" class="toggle-mobile-menu">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </header>
        <?php MenuHelper::render(F::app()->_left_menu, isset($current_directory) ? $current_directory : '')?>
    </div>
</div>