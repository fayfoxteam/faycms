<?php
use fay\helpers\Html;

$this->appendCss($this->url().'css/nivo-slider.css');
?>
<div id="<?php echo $data['elementId']?>">
	<div class="nivo-slider">
	<?php foreach($data['files'] as $d){
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
<script src="<?php echo $this->url()?>js/jquery.nivo.slider.pack.js"></script>
<script>
$(function(){
	$("#<?php echo $data['elementId']?> .nivo-slider").nivoSlider({
		'animSpeed':<?php echo $data['animSpeed']?>,
		'pauseTime':<?php echo $data['pauseTime']?>,
		'directionNav':<?php echo $data['directionNav'] ? 'true' : 'false'?>,
		'effect':'<?php echo $data['effect']?>'
	});
});
</script>