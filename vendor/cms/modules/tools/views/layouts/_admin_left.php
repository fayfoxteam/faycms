<?php

$_admin_left_menu = array(
	array(
		'label'=>'Input',
		'directory'=>'input',
		'sub'=>array(
			array('label'=>'SESSION','router'=>'tools/input/session',),
			array('label'=>'COOKIE','router'=>'tools/input/cookie',),
			array('label'=>'SERVER','router'=>'tools/input/server',),
			array('label'=>'GET','router'=>'tools/input/get',),
			array('label'=>'POST','router'=>'tools/input/post',),
		),
	),
	array(
		'label'=>'CSS',
		'directory'=>'css',
		'sub'=>array(
			array('label'=>'compress','router'=>'tools/css/compress',),
		),
	),
	array(
		'label'=>'Database',
		'directory'=>'database',
		'sub'=>array(
			array('label'=>'Model','router'=>'tools/database/model',),
			array('label'=>'DD','router'=>'tools/database/dd',),
			array('label'=>'DDL','router'=>'tools/database/ddl',),
			array('label'=>'Export','router'=>'tools/database/export',),
			array('label'=>'Compare','router'=>'tools/db-compare/index',),
		),
	),
	array(
		'label'=>'Function',
		'directory'=>'function',
		'sub'=>array(
			array('label'=>'eval','router'=>'tools/function/eval',),
			array('label'=>'date','router'=>'tools/function/date',),
			array('label'=>'unserialize','router'=>'tools/function/unserialize',),
			array('label'=>'json_decode','router'=>'tools/function/json_decode',),
			array('label'=>'urldecode','router'=>'tools/function/urldecode',),
		),
	),
	array(
		'label'=>'Memcache',
		'directory'=>'memcache',
		'sub'=>array(
			array('label'=>'Records','router'=>'tools/memcache/index',),
		),
	),
	array(
		'label'=>'Application',
		'directory'=>'application',
		'sub'=>array(
			array('label'=>'List','router'=>'tools/application/index',),
			array('label'=>'Create','router'=>'tools/application/create',),
		),
	),
);
function showAdminLeftMenu($menu, $actions, $role, $current_directory = ''){
	foreach($menu as $m){?>
	<li <?php if($current_directory == $m['directory']) echo 'class="sel"';?>>
		<a class="menu-head <?php if($current_directory == $m['directory']) echo 'open';?>" href="javascript:;">
			<span class="title"><?php echo $m['label']?></span>
			<?php if($current_directory == $m['directory']){?>
				<span class="selected"></span>
			<?php }?>
			<span class="arrow"></span>
		</a>
		<ul <?php if($current_directory != $m['directory']) echo 'style="display: none;"';?> class="submenu">
		<?php foreach($m['sub'] as $s){?>
			<li <?php if(F::app()->uri->router == $s['router'])echo 'class="sel"'?>>
				<a href="<?php echo F::app()->view->url($s['router'])?>"><?php echo $s['label']?></a>
			</li>
		<?php }?>
		</ul>
	</li>
	<?php }
}?>
<div class="menushadow"></div>
<ul class="menu">
	<?php 
		echo showAdminLeftMenu($_admin_left_menu, F::app()->session->get('actions', array()), F::app()->session->get('role'), $current_directory);
	?>
</ul>