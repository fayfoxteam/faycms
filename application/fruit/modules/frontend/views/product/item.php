<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<div class="g-con">
	<div class="g-mn clearfix">
		<div class="g-mnc">
			<article class="post-item">
				<header>
					<h1 class="post-title"><?php echo Html::encode($post['title'])?></h1>
				</header>
				<div class="post-meta">
					<span>
						发布于：<?php echo Date::niceShort($post['publish_time'])?>
					</span>
					<span>
						阅读数：<?php echo $post['views']?>
					</span>
				</div>
				<div class="post-content">
					<?php echo $post['content'];?>
				</div>
			</article>
		</div>
		<div class="g-aside">
			<aside class="box">
				<div class="box-title">
					<h3 class="sub-title">产品分类</h3>
				</div>
				<div class="box-content">
					<ul class="box-cats">
					<?php foreach($cats as $c){?>
						<li><?php echo Html::link($c['title'], array(
							'product/'.$c['alias']
						))?></li>
					<?php }?>
					</ul>
				</div>
			</aside>
		</div>
	</div>
</div>