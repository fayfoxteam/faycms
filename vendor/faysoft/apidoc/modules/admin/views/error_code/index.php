<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-5">
        <?php echo F::form()->open(array('apidoc/admin/error-code/create'))?>
            <?php $this->renderPartial('_edit_panel');?>
            <div class="form-field">
                <?php echo F::form()->submitLink('新增错误码', array(
                    'class'=>'btn',
                ))?>
            </div>
        <?php echo F::form()->close()?>
    </div>
    <div class="col-7">
        <?php $this->renderPartial('_right', array(
            'listview'=>$listview,
        ))?>
    </div>
</div>