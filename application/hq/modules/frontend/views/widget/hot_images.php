<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/25
 * Time: 下午9:41
 */
use fay\helpers\Html;
use fay\models\File;
?>

<div class="hot-topic">
    <div class="content-header">热点专题</div>
    <div class="content-box">
        <?php foreach ($files as $f) { ?>
            <?= Html::link(Html::img($f['file_id'], File::PIC_ORIGINAL, [
                'width'  => false,
                'height' => false,
                'alt'    => $f['title'],
            ]), str_replace('{$base_url}', $this->config('base_url'), $f['link']), [
                'encode' => false,
                'title'=>Html::encode($f['title']),
                'target'=>'_blank',
            ]) ?>
        <?php } ?>
    </div>
</div>