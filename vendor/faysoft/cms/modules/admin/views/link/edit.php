<?php echo F::form()->open()?>
    <?php $this->renderPartial('_edit_panel', array(
        'cats'=>$cats,
    ))?>
    <div class="form-field">
        <?php echo F::form()->submitLink('编辑链接', array(
            'class'=>'btn',
        ));?>
    </div>
<?php echo F::form()->close()?>