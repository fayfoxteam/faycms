<?php
use fay\models\Category;
use doc\helpers\MenuHelper;

$cats = Category::model()->getTree('fayfox');
?>
<div class="sidebar-menu">
	<div class="sidebar-menu-inner">
		<header class="logo-env">
			<div class="logo">
				<a href="<?php echo $this->url()?>" class="logo-expanded">Faycms</a>
				<a href="<?php echo $this->url()?>" class="logo-collapsed">F</a>
			</div>
			
			<div class="mobile-menu-toggle">
				<a href="javascript:;" class="toggle-mobile-menu">
					<i class="icon-bars"></i>
				</a>
			</div>
		</header>
		<?php MenuHelper::render($cats, isset($cat) ? $cat : array())?>
	</div>
</div>