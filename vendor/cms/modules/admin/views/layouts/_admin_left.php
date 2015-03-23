<?php
use fay\models\tables\Users;

function showAdminLeftMenu($menu, $actions, $role, $current_directory = ''){
	foreach($menu as $m){
		if($role == Users::ROLE_SUPERADMIN){
			$is_show = true;
		}else{
			$is_show = false;
			foreach($m['sub'] as $s){
				if(in_array($s['router'], $actions)){
					$is_show = true;
					break;
				}
			}
		}
		if($is_show){?>
			<li <?php if($current_directory == $m['directory']) echo 'class="sel"';?>>
				<a class="menu-head <?php if($current_directory == $m['directory']) echo 'open';?>" href="javascript:;">
					<?php if(empty($m['icon'])){
						echo '<i class="fa fa-cog"></i>';
					}else{
						echo '<i class="'.$m['icon'].'"></i>';
					}?>
					<span class="title"><?php echo $m['label']?></span>
					<?php if($current_directory == $m['directory']){?>
						<span class="selected"></span>
					<?php }?>
					<span class="arrow"></span>
				</a>
				<ul class="submenu">
				<?php foreach($m['sub'] as $s){?>
					<?php if(in_array($s['router'], $actions) || $role == Users::ROLE_SUPERADMIN){?>
						<li <?php if(F::app()->uri->router == $s['router'])echo 'class="sel"'?>>
							<a href="<?php echo F::app()->view->url($s['router'])?>"><?php echo $s['label']?></a>
						</li>
					<?php }?>
				<?php }?>
				</ul>
			</li>
	<?php }
	}
}
?>
<div class="menushadow"></div>
<ul class="menu">
	<?php
		showAdminLeftMenu(F::app()->_left_menu, F::app()->session->get('actions', array()), F::app()->session->get('role'), $current_directory);
	?>
	<li id="collapse-menu">
		<div id="collapse-button">
			<div></div>
		</div>
		<span>收起菜单</span>
	</li>
</ul>