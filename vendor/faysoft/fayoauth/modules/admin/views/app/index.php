<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-5">
        <?php echo F::form()->open(array('fayoauth/admin/app/create'))?>
        <?php $this->renderPartial('_edit_panel');?>
        <div class="form-field">
            <?php echo F::form()->submitLink('添加APP', array(
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