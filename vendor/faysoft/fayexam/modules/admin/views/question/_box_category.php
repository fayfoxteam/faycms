<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" id="box-category" data-name="category">
    <div class="box-title">
        <h4>分类</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->select('cat_id', HtmlHelper::getSelectOptions($cats), array(
            'class'=>'form-control',
        ))?>
    </div>
</div>