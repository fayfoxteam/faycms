<?php
use fay\models\Menu;
use fay\helpers\Html;
?>
<?php $menu = Menu::model()->getTree('_fruit_top');?>
<header class="g-top">
	<div class="top-inner">
		<div class="g-mn clearfix">
			<div class="top-logo">
				<a href="<?php echo $this->url()?>">
					<img src="<?php echo $this->appStatic('images/logo.png')?>" />
				</a>
			</div>
			<nav class="top-nav">
				<ul>
					<?php $first_menu = array_shift($menu)?>
					<li <?php if(empty($current_header_menu) || $current_header_menu == 'home')echo 'class="crt"'?>><?php echo Html::link($first_menu['title'], $first_menu['link'], array(
						'encode'=>false,
					))?></li>
					<?php foreach($menu as $m){?>
						<li <?php if(isset($current_header_menu) && $current_header_menu == $m['alias'])echo 'class="crt"'?>><?php echo Html::link($m['title'], $m['link'], array(
							'encode'=>false,
							'target'=>$m['target'] ? $m['target'] : false,
						))?></li>
					<?php }?>
				</ul>
				<select id="select-nav">
					<option value="#">--导航--</option>
				<?php foreach($menu as $m){?>
					<option value="<?php echo $m['link']?>" <?php if(isset($current_header_menu) && $current_header_menu == $m['alias'])echo 'selected="selected"'?>><?php echo $m['title']?></option>
				<?php }?>
				</select>
			</nav>
		</div>
	</div>
</header>