<?php
use cms\helpers\MenuHelper;
use fay\helpers\HtmlHelper;
use fay\services\SettingService;
use fay\services\MenuService;
?>
<div class="sidebar-menu <?php
	$admin_sidebar_class = SettingService::service()->get('admin_sidebar_class');
	echo $admin_sidebar_class['class'];
	if(!F::config()->get('debug'))echo ' fixed';
?>" id="sidebar-menu">
	<div class="sidebar-menu-inner">
		<header class="logo-env">
			<div class="logo">
				<?php
					echo HtmlHelper::link('Faycms', array('admin/index/index'), array(
						'class'=>'logo-expanded',
					));
					echo HtmlHelper::link('F', array('admin/index/index'), array(
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
		<?php MenuHelper::render(MenuService::service()->getTree('_tools_main'), isset($current_directory) ? $current_directory : '')?>
	</div>
</div>