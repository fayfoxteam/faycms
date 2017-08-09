<div class="clearfix">
    <div class="half-left">
    <?php
        echo $this->renderPartial('editor/_field_title');
        echo $this->renderPartial('editor/_field_cat');
        echo $this->renderPartial('editor/_field_tags');
        echo $this->renderPartial('editor/_field_copyright');
    ?>
    </div>
    <div class="half-right">
    <?php
        echo $this->renderPartial('editor/_field_video');
        echo $this->renderPartial('editor/_field_file');
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
                <label>博客正文:</label>
                <span class="tip">在这里填写详细的文章内容分享给大家</span>
            </div>
            <?php echo \F::form()->textarea('content', array(
                'id'=>'visual-editor',
            ))?>
        </fieldset>
    </div>
</div>