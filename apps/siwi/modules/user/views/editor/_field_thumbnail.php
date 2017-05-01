<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<fieldset class="form-field">
    <div class="title">
        <label>封面:</label>
        <span class="tip">封面相当于人到脸</span>
    </div>
    <div class="thumbnail-container">
        <?php 
        echo \F::form()->inputHidden('thumbnail');
        if(\F::form()->getData('thumbnail')){
            echo HtmlHelper::link(HtmlHelper::img(\F::form()->getData('thumbnail'), FileService::PIC_RESIZE, array(
                'dw'=>254,
                'dh'=>217,
            )), FileService::getUrl(\F::form()->getData('thumbnail')), array(
                'encode'=>false,
                'class'=>'fancybox-image',
                'title'=>false,
            ));
        }else{
            echo HtmlHelper::img($this->url().'static/siwi/images/avatar-preview.jpg');
        }?>
        <div class="meta">
            <h1 class="title"><?php echo \F::form()->getData('title')?></h1>
            <span class="cat-1"></span>
            -
            <span class="cat-2"></span>
        </div>
    </div>
    <div id="upload-thumbnail-container">
        <a href="javascript:;" id="upload-thumbnail-link" class="btn-blue upload-thumbnail-link">上传封面</a>
    </div>
</fieldset>