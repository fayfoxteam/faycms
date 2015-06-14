<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="widget widget-images" id="widget-<?php echo Html::encode($alias)?>">
	<ul>
	<?php foreach($files as $f){
		if(empty($f['link'])){
			$f['link'] = 'javascript:;';
		}
		echo Html::link(Html::img($f['file_id'], File::PIC_ORIGINAL, array(
			'width'=>false,
			'height'=>false,
			'alt'=>Html::encode($f['title']),
		)), str_replace('{$base_url}', $this->config('base_url'), $f['link']), array(
			'encode'=>false,
			'title'=>Html::encode($f['title']),
			'wrapper'=>'li',
			'target'=>'_blank',
		));
	}?>
	</ul>
</div>