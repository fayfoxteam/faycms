<?php
use apidoc\models\tables\ApidocAppsTable;
use fay\helpers\ArrayHelper;
?>
<div class="box" id="box-app" data-name="app">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>所属应用</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->select(
            'app_id',
            ArrayHelper::column(
                ApidocAppsTable::model()->fetchAll(array(), 'id,name', 'id'),
                'name',
                'id'
            ),
            array(
                'class'=>'form-control mw400',
            ))?>
    </div>
</div>