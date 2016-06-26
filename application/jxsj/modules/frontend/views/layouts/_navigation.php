<?php
use fay\services\Menu;
use fay\helpers\Html;
use fay\models\Category;
use fay\models\Page;

$menu = Menu::service()->getTree('_jxsj_top');
?>
<nav class="g-nav">
	<div class="w1000">
		<ul>
			<li class="first nav-i">
				<?php echo Html::link('网站首页', array(null), array(
					'title'=>false,
				))?>
			</li>
			<?php
				//静态页面
				$cat_about = Category::model()->getByAlias('about', 'title');
				$pages = Page::model()->getByCatAlias('about', 5);
				echo '<li class="nav-i">', Html::link($cat_about['title'], 'javascript:;', array(
					'class'=>'nav-p',
					'title'=>false,
				));
				echo '<ul class="nav-c">';
				foreach($pages as $p){
					echo '<li>', Html::link($p['title'], array('page/'.$p['id']), array(
						'title'=>false,
					)), '</li>';
				}
				echo '</ul>';
				echo '</li>';
				//文章分类列表
				$cats = Category::model()->getTree('_system_post');
				foreach($cats as $cat){
					if(!$cat['is_nav'])continue;
					echo '<li class="nav-i">', Html::link($cat['title'], array('cat/'.$cat['id']), array(
						'class'=>'nav-p',
						'title'=>false,
					));
					if(!empty($cat['children'])){
						echo '<ul class="nav-c">';
						foreach($cat['children'] as $c){
							if(!$c['is_nav'])continue;
							echo '<li>', Html::link($c['title'], array('cat/'.$c['id']), array(
								'title'=>false,
							)), '</li>';
						}
						echo '</ul>';
					}
				}
				echo '</li>';
			?>
			<li class="first nav-i">
				<?php echo Html::link('互动交流', array('chat'), array(
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