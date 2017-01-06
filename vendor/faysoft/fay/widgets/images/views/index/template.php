<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;

/**
 * @var $widget \fay\widgets\images\controllers\IndexController
 * @var $files array
 */
?>
<div class="widget widget-images" id="widget-<?php echo HtmlHelper::encode($widget->alias)?>">
	<ul>
	<?php foreach($files as $f){
		if(empty($f['link'])){
			$f['link'] = 'javascript:;';
		}
		echo HtmlHelper::link(HtmlHelper::img($f['file_id'], FileService::PIC_ORIGINAL, array(
			'width'=>false,
			'height'=>false,
			'alt'=>HtmlHelper::encode($f['title']),
		)), str_replace('{$base_url}', \F::config()->get('base_url'), $f['link']), array(
			'encode'=>false,
			'title'=>HtmlHelper::encode($f['title']),
			'wrapper'=>'li',
			'target'=>'_blank',
		));
	}?>
	</ul>
</div>