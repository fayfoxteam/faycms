<?php
use fay\models\File;
use fay\models\tables\Files;
use fay\models\Qiniu;
use fay\helpers\Html;
?>
<div class="jq-camera-container">
	<div class="camera_wrap camera_azure_skin jq-camera">
	<?php foreach($data['files'] as $d){
		$file = Files::model()->find($d['file_id']);
		if($file['qiniu']){
			$data_src = Qiniu::model()->getUrl($file);
		}else{
			$data_src = File::model()->getUrl($file);
		}
		echo Html::tag('div', array(
			'data-src'=>$data_src,
			'data-link'=>empty($d['link']) ? false : $d['link'],
		), '');
	}?>
	</div>
</div>
<?php $this->appendCss($this->url().'css/jquery.camera.css')?>
<script src="<?php echo $this->url()?>js/jquery.camera.js"></script>
<script src="<?php echo $this->url()?>js/jquery.easing.1.3.js"></script>
<style>
.jq-camera-container{height:<?php echo $data['height']?>px;}
</style>
<script>
$(function(){
	$(".jq-camera").camera({
		'height':'<?php echo is_numeric($data['height']) ? $data['height'].'px' : $data['height']?>',
		'easing':'swing',
		'loader':'none',
		'pagination':false,
		'playPause':false,
		'transPeriod':<?php echo $data['transPeriod']?>,
		'time':<?php echo $data['time']?>,
		'fx':'<?php echo $data['fx']?>'
	});
});
</script>