<?php
?>
<div class="box" id="box-thumbnail" data-name="thumbnail">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>缩略图</h4>
    </div>
    <div class="box-content">
        <?php $this->renderPartial('file/_upload_image', array(
            'cat'=>'page',
        ))?>
    </div>
</div>