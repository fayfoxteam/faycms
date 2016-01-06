<?php 
use fay\helpers\Html;
use fay\models\Menu;
use fay\models\Option;
use fay\core\Uri;
?>
<header class="g-top">
	<div class="centered-wrapper">
		<span class="top-logo">
			<a href="<?php echo $this->url()?>">
				<img src="<?php echo $this->appStatic('images/logo.png')?>" alt="<?php echo Option::get('site:sitename')?>" />
				<span>Pharmrich</span>
			</a>
		</span>
		<nav class="top-nav fr">
			<ul><?php
				$top_nav = Menu::model()->getTree('_top_nav');
				foreach($top_nav as $nav){
					echo Html::link($nav['title'], $nav['link'], array(
						'target'=>$nav['target'],
						'wrapper'=>'li',
						'title'=>false,
					));
				}
			?>
				<li class="phone-container"><span class="phone-number" style="display:none"><?php echo Option::get('site:phone')?></span><span class="phone"></span></li>
				<li class="toggle-phone-menu"><a href="javascript:;"><i class="fa fa-bars"></i></a></li>
			</ul>
		</nav>
	</div>
</header>
<?php if(Uri::getInstance()->router == 'frontend/index/index'){?>
	<div class="cf"><?php F::widget()->load('index-slides-camera')?></div>
<?php }?>
<div class="g-search cf">
	<h4>SEARCH</h4>
	<form id="search-form" action="<?php echo $this->url('search')?>" method="get"><?php
		echo Html::inputText('keywords', F::input()->get('keywords', 'trim'), array(
			'placeholder'=>'Enter Keywords',
			'id'=>'keywords',
		));
		echo Html::link('', 'javascript:;', array(
			'id'=>'search-form-submit',
			'title'=>false,
		));
	?></form>
</div>
<nav class="g-nav">
	<div class="centered-wrapper">
		<ul class="cf"><?php
			$menus = Menu::model()->getTree('_menu');
			foreach($menus as $menu){
				echo Html::link($menu['title'], $menu['link'], array(
					'class'=>isset($current_header_menu) && $current_header_menu == $menu['alias'] ? 'crt' : false,
					'target'=>$nav['target'],
					'wrapper'=>'li',
					'title'=>false,
				));
			}
		?></ul>
	</div>
</nav>