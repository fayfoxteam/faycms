<?php 
use fay\helpers\Html;
use fay\models\Menu;
use fay\helpers\ArrayHelper;

$menus = Menu::model()->getTree();
?>
<header class="g-hd" id="g-hd">
	<div class="centered-wrapper">
		<div class="m-logo">
			<a href="<?php echo $this->url()?>"><img src="<?php echo $this->appStatic('images/logo.gif')?>" /></a>
		</div>
		<nav class="nav">
			<ul><?php
				foreach($menus as $menu){
					echo Html::link($menu['title'], $menu['link'], array(
						'class'=>isset($current_header_menu) && $current_header_menu == $menu['alias'] ? 'crt' : false,
						'wrapper'=>'li',
					));
				}
			?></ul>
			<?php echo Html::select('', ArrayHelper::column($menus, 'title', 'link'), isset($current_header_menu) ? $current_header_menu : '', array(
				'class'=>'select-menu',
			))?>
		</nav>
	</div>
</header>