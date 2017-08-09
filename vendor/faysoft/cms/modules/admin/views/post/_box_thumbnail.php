<div class="box" id="box-thumbnail" data-name="thumbnail">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>缩略图</h4>
    </div>
    <div class="box-content">
        <?php echo $this->renderPartial('file/_upload_image', array(
            'cat'=>'post',
        ))?>
    </div>
</div>