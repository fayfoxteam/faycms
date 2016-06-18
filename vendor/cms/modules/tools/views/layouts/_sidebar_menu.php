<?php
use cms\helpers\MenuHelper;
use fay\helpers\Html;
use fay\models\Setting;
use fay\models\Menu;
?>
<div class="sidebar-menu <?php
	$admin_sidebar_class = Setting::model()->get('admin_sidebar_class');
	echo $admin_sidebar_class['class'];
	if(!F::config()->get('debug'))echo ' fixed';
?>" id="sidebar-menu">
	<div class="sidebar-menu-inner">
		<header class="logo-env">
			<div class="logo">
				<?php
					echo Html::link('Faycms', array('admin/index/index'), array(
						'class'=>'logo-expanded',
					));
					echo Html::link('F', array('admin/index/index'), array(
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
		<?php MenuHelper::render(Menu::model()->getTree('_tools_main'), isset($current_directory) ? $current_directory : '')?>
	</div>
</div>