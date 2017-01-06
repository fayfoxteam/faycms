<?php
use fay\services\MenuService;
use fay\helpers\HtmlHelper;
use fay\services\CategoryService;
use fay\services\PageService;

$menu = MenuService::service()->getTree('_jxsj_top');
?>
<nav class="g-nav">
	<div class="w1000">
		<ul>
			<li class="first nav-i">
				<?php echo HtmlHelper::link('网站首页', array(null), array(
					'title'=>false,
				))?>
			</li>
			<?php
				//静态页面
				$cat_about = CategoryService::service()->getByAlias('about', 'title');
				$pages = PageService::service()->getByCatAlias('about', 5);
				echo '<li class="nav-i">', HtmlHelper::link($cat_about['title'], 'javascript:;', array(
					'class'=>'nav-p',
					'title'=>false,
				));
				echo '<ul class="nav-c">';
				foreach($pages as $p){
					echo '<li>', HtmlHelper::link($p['title'], array('page/'.$p['id']), array(
						'title'=>false,
					)), '</li>';
				}
				echo '</ul>';
				echo '</li>';
				//文章分类列表
				$cats = CategoryService::service()->getTree('_system_post');
				foreach($cats as $cat){
					if(!$cat['is_nav'])continue;
					echo '<li class="nav-i">', HtmlHelper::link($cat['title'], array('cat/'.$cat['id']), array(
						'class'=>'nav-p',
						'title'=>false,
					));
					if(!empty($cat['children'])){
						echo '<ul class="nav-c">';
						foreach($cat['children'] as $c){
							if(!$c['is_nav'])continue;
							echo '<li>', HtmlHelper::link($c['title'], array('cat/'.$c['id']), array(
								'title'=>false,
							)), '</li>';
						}
						echo '</ul>';
					}
				}
				echo '</li>';
			?>
			<li class="first nav-i">
				<?php echo HtmlHelper::link('互动交流', array('chat'), array(
					'title'=>false,
				))?>
			</li>
		</ul>
	</div>
</nav>
<script>
$(function(){
	$('.g-nav').on('mouseover', '.nav-i', function(){
		$(this).find('.nav-c').slideDown('fast');
	});
	$('.g-nav').on('mouseleave', '.nav-i', function(){
		$(this).find('.nav-c').slideUp('fast');
	});
});
</script>