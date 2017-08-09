<div class="clearfix">
    <div class="half-left">
    <?php
        echo $this->renderPartial('editor/_field_title');
        echo $this->renderPartial('editor/_field_file');
        echo $this->renderPartial('editor/_field_tags');
    ?>
    </div>
    <div class="half-right">
    <?php
        echo $this->renderPartial('editor/_field_cat');
        echo $this->renderPartial('editor/_field_abstract');
    ?>
    </div>
</div>
<div class="clearfix">
    <div class="gold-left">
        <?php echo $this->renderPartial('editor/_field_thumbnail');?>
    </div>
    <div class="gold-right">
        <fieldset class="form-field">
            <div class="title">
                <label>上传预览图</label>
            </div>
            <div class="preview-container <?php if(\F::form()->getData('content'))echo 'uploaded';?>">
                <?php echo \F::form()->textarea('content', array(
                    'class'=>'hide',
                ))?>
                <div id="upload-preview-container" class="upload-panel clearfix">
                    <a class="upload-link" id="upload-preview-link" href="javascript:;" <?php if(\F::form()->getData('content'))echo 'style="display:none;"';?>>
                        <i class="icon-plus"></i>
                        <span class="click-to-upload">点击上传</span>
                        <span class="desc">支持&nbsp;jpg,gif,png</span>
                        <span class="progress-bar"><span class="progress-bar-percent"></span></span>
                    </a>
                    <?php echo \F::form()->getData('content')?>
                </div>
                <div class="remove-panel hide">
                    <a class="remove-link" href="javascript:;">
                        <i class="icon-cross"></i>
                        <span class="click-to-remove">删除图片</span>
                    </a>
                </div>
            </div>
        </fieldset>
    </div>
</div>