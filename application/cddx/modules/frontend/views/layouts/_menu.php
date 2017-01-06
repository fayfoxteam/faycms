<?php
use fay\helpers\Html;
use fay\services\CategoryService;

$cats = CategoryService::service()->getTree('__root__');
?>
<nav class="g-nav">
	<div class="w1000">
		<ul>
			<li class="nav-i"><?php echo Html::link('网站首页', null)?></li>
			<?php
				//文章分类列表
				foreach($cats as $m){
					if(!$m['is_nav']) continue;
					echo '<li class="nav-i">', Html::link($m['title'], $m['description'] ? $m['description'] : array('cat-'.$m['id']), array(
						'class'=>'nav-p',
						'title'=>false,
						'target'=>$m['description'] ? '_blank' : false,
					));
					if(!empty($m['children'])){
						echo '<ul class="nav-c">';
						foreach($m['children'] as $m2){
							if(!$m2['is_nav']) continue;
							echo '<li>', Html::link($m2['title'], $m2['description'] ? $m2['description'] : array('cat-'.$m2['id']), array(
								'title'=>false,
								'target'=>$m2['description'] ? '_blank' : false,
							)), '</li>';
						}
						echo '</ul>';
					}
				}
				echo '</li>';
			?>
		</ul>
	</div>
</nav>
<script>
$(function(){
	$('.g-nav').on('mouseover', '.nav-i', function(){
		$(this).find('.nav-c').stop(true, true).slideDown('fast');
	});
	$('.g-nav').on('mouseleave', '.nav-i', function(){
		$(this).find('.nav-c').stop(true, true).slideUp('fast');
	});
});
</script>