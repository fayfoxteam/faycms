<?php
use fay\helpers\Html;

$this->appendCss($this->url().'css/nivo-slider.css');
?>
<div id="<?php echo $config['elementId']?>">
	<div class="nivo-slider">
	<?php foreach($config['files'] as $d){
		if(empty($d['link'])){
			$d['link'] = 'javascript:;';
		}
		echo Html::link(Html::img($d['file_id'], 1, array(
			'alt'=>Html::encode($d['title']),
			'title'=>Html::encode($d['title']),
			'width'=>false,
			'height'=>false,
		)), $d['link'], array(
			'encode'=>false,
			'title'=>Html::encode($d['title']),
		));
	}?>
	</div>
</div>
<script src="<?php echo $this->assets('js/jquery.nivo.slider.pack.js')?>"></script>
<script>
$(function(){
	$("#<?php echo $config['elementId']?> .nivo-slider").nivoSlider({
		'animSpeed':<?php echo $config['animSpeed']?>,
		'pauseTime':<?php echo $config['pauseTime']?>,
		'directionNav':<?php echo $config['directionNav'] ? 'true' : 'false'?>,
		'effect':'<?php echo $config['effect']?>'
	});
});
</script>