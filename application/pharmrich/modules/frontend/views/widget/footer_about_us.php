<?php
use fay\helpers\Html;

/**
 * @var $page array
 */
?>
<div class="m-about">
	<h3><?php echo Html::encode($page['title'])?></h3>
	<div class="content"><?php echo Html::encode($page['abstract'])?></div>
</div>