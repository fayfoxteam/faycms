<?php
use fay\services\File;
use fay\models\tables\Files;
use fay\services\Qiniu;
use fay\helpers\Html;
use fay\helpers\StringHelper;
?>
<div class="jq-camera-container">
	<div class="camera_wrap camera_azure_skin jq-camera">
	<?php foreach($files as $f){
		$file = Files::model()->find($f['file_id']);
		if($file['qiniu']){
			$data_src = Qiniu::service()->getUrl($file);
		}else{
			$data_src = File::getUrl($file);
		}
		echo Html::tag('div', array(
			'data-src'=>$data_src,
			'data-link'=>empty($f['link']) ? false : $f['link'],
		), '');
	}?>
	</div>
</div>
<?php $this->appendCss($this->assets('css/jquery.camera.css'))?>
<script src="<?php echo $this->assets('js/jquery.camera.js')?>"></script>
<script src="<?php echo $this->assets('js/jquery.easing.1.3.js')?>"></script>
<script>
$(function(){
	$(".jq-camera").camera({
		'height':'<?php echo StringHelper::isInt($config['height']) ? $config['height'].'px' : $config['height']?>',
		'easing':'swing',
		'loader':'none',
		'pagination':false,
		'playPause':false,
		'transPeriod':<?php echo $config['transPeriod']?>,
		'time':<?php echo $config['time']?>,
		'fx':'<?php echo $config['fx']?>'
	});
});
</script>