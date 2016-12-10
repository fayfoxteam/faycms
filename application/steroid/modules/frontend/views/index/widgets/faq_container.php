<?php
/**
 * @var $config
 * @var $posts
 */

//获取分类描述
?>
<section class="section" id="section-faq">
	<div class="bg" style="background-image:url(<?php echo \fay\services\File::getUrl($config['file_id'])?>)">
		<?php F::widget()->load('faq-list')?>
	</div>
</section>