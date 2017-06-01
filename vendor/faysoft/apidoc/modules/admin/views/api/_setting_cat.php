<?php echo F::form('setting')->open(array('admin/system/setting'))?>
    <?php echo F::form('setting')->inputHidden('_key')?>
    <div class="form-field">
        <label class="title bold">默认显示深度</label>
        <?php echo F::form('setting')->inputNumber('default_dep', array(
            'class'=>'form-control w50',
            'min'=>1,
            'max'=>8,
        ))?>
        <p class="fc-grey">打开页面时默认显示分类层级深度</p>
    </div>
    <div class="form-field">
        <?php echo F::form('setting')->submitLink('提交', array(
            'class'=>'btn btn-sm',
        ))?>
    </div>
<?php echo F::form('setting')->close()?>