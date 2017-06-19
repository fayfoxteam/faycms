<?php
use cms\services\file\FileService;
use fay\helpers\HtmlHelper;

?>
<div class="box" id="box-files" data-name="files">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>配图</h4>
    </div>
    <div class="box-content">
        <div id="upload-file-container">
            <?php echo HtmlHelper::link('上传图片', 'javascript:;', array(
                'class'=>'btn',
                'id'=>'upload-file-link',
            ))?>
        </div>
        <div class="dragsort-list file-list">
        <?php if(!empty($files)){?>
            <?php foreach($files as $f){?>
                <div class="dragsort-item">
                    <?php echo HtmlHelper::inputHidden('files[]', $f['file_id'])?>
                    <a class="dragsort-rm" href="javascript:"></a>
                    <a class="dragsort-item-selector"></a>
                    <div class="dragsort-item-container">
                        <span class="fl"><?php
                            $full_file_path = FileService::getUrl($f['file_id']);
                            echo HtmlHelper::link(HtmlHelper::img($f['file_id'], FileService::PIC_THUMBNAIL), $full_file_path, array(
                                'encode'=>false,
                                'title'=>false,
                                'data-fancybox'=>'images',
                                'data-caption'=>HtmlHelper::encode(HtmlHelper::encode($f['description'])),
                            ));
                        ?></span>
                        <div class="ml120">
                            <?php echo HtmlHelper::textarea("description[{$f['file_id']}]", $f['description'], array(
                                'class'=>'form-control file-desc autosize',
                                'placeholder'=>'照片描述',
                            ));?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php }?>
        <?php }?>
        </div>
        <div class="clear"></div>
    </div>
</div>