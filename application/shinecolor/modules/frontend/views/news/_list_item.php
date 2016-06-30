<?php
use fay\helpers\Html;
use fay\services\Post;
?>
<li class="disc">
	<a href="<?php echo Post::service()->getLink($data, 'news')?>">
		<time class="fr"><?php echo date('Y-m-d', $data['publish_time'])?></time>
		<span><?php echo Html::encode($data['title'])?></span>
	</a>
</li>