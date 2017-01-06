<?php
use fay\helpers\Html;
use fay\services\PostService;
?>
<li class="disc">
	<a href="<?php echo $this->url('news/'.$data['id']);?>">
		<time class="fr"><?php echo date('Y-m-d', $data['publish_time'])?></time>
		<span><?php echo Html::encode($data['title'])?></span>
	</a>
</li>