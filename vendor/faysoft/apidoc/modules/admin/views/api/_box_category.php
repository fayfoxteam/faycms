<?php
use cms\services\CategoryService;
use fay\helpers\HtmlHelper;

/**
 * @var $cats array
 */
?>
<div class="box" id="box-category" data-name="category">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>分类</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->select('cat_id', array(
            CategoryService::service()->getIdByAlias('_system_api')=>'--未分类--',
        ) + HtmlHelper::getSelectOptions($cats), array(
            'class'=>'form-control mw400',
        ))?>
    </div>
</div>