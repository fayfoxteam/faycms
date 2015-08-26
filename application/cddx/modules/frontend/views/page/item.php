<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<div class="inner cf">
	<div class="breadcrumbs">
		<?php
		echo Html::link('网站首页', array('')),
			' &gt; ',
			Html::encode($page['title']);
		?>
	</div>
	<div class="g-sd">
		<div class="cat-list">
			<h3><?php echo Html::encode($left_cats['title'])?></h3>
			<ul>
			<?php foreach($left_cats['children'] as $c){
				echo Html::link($c['title'], array('cat-'.$c['id']), array(
					'wrapper'=>'li',
				));
			}?>
			</ul>
		</div>
	</div>
	<div class="g-mn">
		<h1 class="post-title"><?php echo Html::encode($page['title'])?></h1>
		<div class="post-meta">
			<span>发布时间：<?php echo Date::niceShort($page['create_time'])?></span>
			<span>阅读数：<?php echo $page['views']?></span>
		</div>
		<div class="post-content"><?php echo $page['content']?></div>
	</div>
</div>