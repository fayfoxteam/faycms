<?php
use cms\helpers\MenuHelper;
use fay\models\Menu;
use fay\helpers\Html;

$menus = Menu::model()->getTree('_admin_main');
?>
<div class="sidebar-menu">
	<div class="sidebar-menu-inner">
		<header class="logo-env">
			<div class="logo">
				<?php
					echo Html::link('Faycms', null, array(
						'class'=>'logo-expanded',
					));
					echo Html::link('F', null, array(
						'class'=>'logo-collapsed',
					));
				?>
			</div>
			
			<div class="mobile-menu-toggle">
				<a href="javascript:;" class="toggle-mobile-menu">
					<i class="fa fa-bars"></i>
				</a>
			</div>
		</header>
		<?php MenuHelper::render($menus, isset($current_directory) ? $current_directory : '')?>
	</div>
</div>