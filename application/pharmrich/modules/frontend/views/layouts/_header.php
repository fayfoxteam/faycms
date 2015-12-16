<?php 
use fay\helpers\Html;
use fay\models\Menu;
use fay\models\Option;
use fay\helpers\ArrayHelper;
use fay\core\Uri;
?>
<header class="g-top">
	<div class="centered-wrapper">
		<nav class="top-nav fr">
			<ul><?php
				$top_nav = Menu::model()->getTree('_top_nav');
				foreach($top_nav as $nav){
					echo Html::link($nav['title'], $nav['link'], array(
						'target'=>$nav['target'],
						'wrapper'=>'li',
					));
				}
			?>
				<li class="phone-container"><span class="phone-number" style="display:none"><?php echo Option::get('site:phone')?></span><span class="phone"></span></li>
			</ul>
		</nav>
	</div>
</header>
<?php if(Uri::getInstance()->router == 'frontend/index/index'){?>
	<div class="cf"><?php F::widget()->load('index-slides-camera')?></div>
<?php }?>
<div class="g-search cf">
	<h4>搜索 SEARCH</h4>
	<form id="search-form" action="<?php echo $this->url('search')?>"><?php
		echo Html::inputText('keywords', F::input()->get('keywords', 'trim'), array(
			'placeholder'=>'输入关键词',
			'id'=>'keywords',
		));
		echo Html::link('', 'javascript:;', array(
			'id'=>'search-form-submit',
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
				));
			}
		?></ul>
	</div>
</nav>
<?php echo Html::select('', ArrayHelper::column($menus, 'title', 'link'), isset($current_header_menu) ? $current_header_menu : '', array(
	'class'=>'select-menu',
))?>