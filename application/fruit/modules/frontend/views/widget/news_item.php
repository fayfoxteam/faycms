<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>

<article class="post-item">
	<header>
		<h1 class="post-title"><?php echo HtmlHelper::encode($post['title'])?></h1>
	</header>
	<div class="post-meta">
		<span>
			发布于：<?php echo DateHelper::niceShort($post['publish_time'])?>
		</span>
		<span>
			阅读数：<?php echo $post['views']?>
		</span>
	</div>
	<div class="post-content">
		<?php echo $post['content'];?>
	</div>
</article>
