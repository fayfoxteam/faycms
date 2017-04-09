<?php
use fay\helpers\HtmlHelper;
?>
<fieldset class="form-field">
    <div class="title">
        <label class="title">分类:</label>
        <span class="tip">选择分类可以被其他设计师更容易找到</span>
    </div>
    <?php 
    echo \F::form()->select('parent_cat', array(
        ''=>'--请选择分类--',
    ) + HtmlHelper::getSelectOptions($cats), array(
        'class'=>'inputxt short fl',
    ));
    echo \F::form()->select('cat_id', array(
        ''=>'--请先选择左侧分类--',
    ), array(
        'class'=>'inputxt short fr',
        'ignore'=>false,
        'nullmsg'=>'请选择分类',
        'errormsg'=>'分类信息异常',
    ));
    ?>
</fieldset>