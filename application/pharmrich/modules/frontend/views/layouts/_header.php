<?php 
use fay\helpers\HtmlHelper;
use fay\services\MenuService;
use fay\services\OptionService;
use fay\core\Uri;
?>
<header class="g-top">
	<div class="centered-wrapper">
		<span class="top-logo">
			<a href="<?php echo $this->url()?>">
				<img src="<?php echo $this->appStatic('images/logo.png')?>" alt="<?php echo OptionService::get('site:sitename')?>" />
				<span>Pharmrich</span>
			</a>
		</span>
		<nav class="top-nav fr">
			<ul><?php
				$top_nav = MenuService::service()->getTree('_top_nav');
				foreach($top_nav as $nav){
					echo HtmlHelper::link($nav['title'], $nav['link'], array(
						'target'=>$nav['target'],
						'wrapper'=>'li',
						'title'=>false,
					));
				}
			?>
			<?php if($phone = OptionService::get('site:phone')){?>
				<li class="phone-container"><span class="phone-number" style="display:none"><?php echo $phone?></span><span class="phone"></span></li>
			<?php }?>
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
		echo HtmlHelper::inputText('keywords', F::input()->get('keywords', 'trim'), array(
			'placeholder'=>'Enter Keywords',
			'id'=>'keywords',
		));
		echo HtmlHelper::link('', 'javascript:;', array(
			'id'=>'search-form-submit',
			'title'=>false,
		));
	?></form>
</div>
<nav class="g-nav">
	<div class="centered-wrapper">
		<ul class="cf"><?php
			$menus = MenuService::service()->getTree('_menu');
			foreach($menus as $menu){
				echo HtmlHelper::link($menu['title'], $menu['link'], array(
					'class'=>isset($current_header_menu) && $current_header_menu == $menu['alias'] ? 'crt' : false,
					'target'=>$nav['target'],
					'wrapper'=>'li',
					'title'=>false,
				));
			}
		?></ul>
	</div>
</nav>