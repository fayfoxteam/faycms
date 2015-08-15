<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/28
 * Time: 下午9:03
 */
use fay\helpers\Html;
use fay\models\File;
?>

<div class="content-header">快速通道</div>
<div class="content-box">
    <?php
     foreach ($files as $f) {
         ?>
         <div class="property">
             <?= Html::link(Html::img($f['file_id'], File::PIC_ORIGINAL, [
                 'width'  => false,
                 'height' => false,
                 'alt'    => $f['title'],
             ]), str_replace('{$base_url}', $this->config('base_url'), $f['link']), [
                 'encode' => false,
                 'title'=>Html::encode($f['title']),
                 'target'=>'_blank',
             ]) ?>
         </div>
     <?php
     }
    ?>


</div>