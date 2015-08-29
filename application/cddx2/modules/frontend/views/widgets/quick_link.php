<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div id="quick-link">
	<div class="ql-title"><img src="<?php echo $this->appStatic('images/quick-link.png')?>"></div>
	<ul>
	<?php foreach($files as $f){
		if(empty($f['link'])){
			$f['link'] = 'javascript:;';
		}
		echo Html::link(Html::img($f['file_id'], File::PIC_ORIGINAL, array(
			'width'=>false,
			'height'=>false,
			'alt'=>Html::encode($f['title']),
		)), str_replace('{$base_url}', \F::config()->get('base_url'), $f['link']), array(
			'encode'=>false,
			'title'=>Html::encode($f['title']),
			'wrapper'=>'li',
			'target'=>'_blank',
		));
	}?>
	</ul>
</div>