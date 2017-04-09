<?php
use cms\services\MenuService;

$menu = MenuService::service()->getTree('top');
?>

<nav id="site-navigation">
    <div class="nav-menu">
        <ul>
        <?php $first_menu = array_shift($menu)?>
        <li class="first  fixpng <?php if(!isset($current_directory) || $current_directory == $first_menu['alias'])echo 'sel'?>">
            <a title="<?php echo $first_menu['title']?>" href="<?php echo $first_menu['link']?>">
                <span><?php echo $first_menu['title']?></span>
                <em><?php echo $first_menu['sub_title']?></em>
            </a>
        </li>
        <?php foreach($menu as $m){?>
            <li class="fixpng <?php if(isset($current_directory) && $current_directory == $m['alias'])echo 'sel'?>">
                <a title="<?php echo $m['title']?>" href="<?php echo $m['link']?>">
                    <span><?php echo $m['title']?></span>
                    <em><?php echo $m['sub_title']?></em>
                </a>
            </li>
        <?php }?>
        </ul>
    </div>
</nav>