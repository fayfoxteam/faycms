<?php
use cms\services\file\FileService;
use cms\models\tables\FilesTable;
use cms\services\file\QiniuService;
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;

/**
 * @var $widget \fay\widgets\jq_camera\controllers\IndexController
 * @var $files array
 */

$element_id = $widget->config['element_id'] ? $widget->config['element_id'] : $widget->alias;
?>
<div class="jq-camera-container" id="<?php echo $element_id?>">
    <div class="camera_wrap camera_azure_skin jq-camera">
    <?php foreach($files as $f){
        $file = FilesTable::model()->find($f['file_id']);
        if($file['qiniu']){
            $data_src = QiniuService::service()->getUrl($file);
        }else{
            $data_src = FileService::getUrl($file);
        }
        echo HtmlHelper::tag('div', array(
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
    $("#<?php echo $element_id?> .jq-camera").camera({
        'height':'<?php echo StringHelper::isInt($widget->config['height']) ? $widget->config['height'].'px' : $widget->config['height']?>',
        'easing':'swing',
        'loader':'none',
        'pagination':false,
        'playPause':false,
        'transPeriod':<?php echo $widget->config['transPeriod']?>,
        'time':<?php echo $widget->config['time']?>,
        'fx':'<?php echo $widget->config['fx']?>'
    });
});
</script>