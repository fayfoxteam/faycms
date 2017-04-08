<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<article class="post-item">
	<header>
		<h1 class="post-title"><?php echo HtmlHelper::encode($post['post']['title'])?></h1>
	</header>
	<div class="post-meta">
		<span>
			发布于：<?php echo DateHelper::niceShort($post['post']['publish_time'])?>
		</span>
		<span>
			阅读数：<?php echo $post['meta']['views']?>
		</span>
	</div>
	<div class="post-content">
		<?php echo $post['post']['content'];?>
	</div>
</article>