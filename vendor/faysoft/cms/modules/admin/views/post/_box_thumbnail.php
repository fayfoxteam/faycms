<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<div class="box" id="box-thumbnail" data-name="thumbnail">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>缩略图</h4>
    </div>
    <div class="box-content">
        <div id="thumbnail-container" class="mb10">
            <a href="javascript:" id="upload-thumbnail" class="btn">设置缩略图</a>
        </div>
        <div id="thumbnail-preview-container"><?php 
            echo F::form()->inputHidden('thumbnail', array(), 0);
            $thumbnail = F::form()->getData('thumbnail', 0);
            if(!empty($thumbnail)){
                echo HtmlHelper::link(HtmlHelper::img($thumbnail, FileService::PIC_RESIZE, array(
                    'dw'=>257,
                )), FileService::getUrl($thumbnail), array(
                    'encode'=>false,
                    'class'=>'block',
                    'title'=>'点击查看原图',
                    'data-fancybox'=>null,
                    'data-caption'=>'文章缩略图',
                ));
                echo HtmlHelper::link('移除缩略图', 'javascript:;', array(
                    'class'=>'remove-image-link'
                ));
            }
        ?></div>
    </div>
</div>