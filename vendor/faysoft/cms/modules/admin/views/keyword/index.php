<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-5">
        <?php echo F::form()->open(array('cms/admin/keyword/create'))?>
            <div class="form-field">
                <label class="title bold">关键词<em class="required">*</em></label>
                <?php echo F::form()->inputText('keyword', array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title bold">链接地址<em class="required">*</em></label>
                <?php echo F::form()->inputText('link', array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <?php echo F::form()->submitLink('添加关键词', array(
                    'class'=>'btn',
                ))?>
            </div>
        <?php echo F::form()->close()?>
    </div>
    <div class="col-7">
        <?php echo $this->renderPartial('_right', array(
            'listview'=>$listview,
        ));?>
    </div>
</div>