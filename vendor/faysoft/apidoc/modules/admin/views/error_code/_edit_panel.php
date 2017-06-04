<?php
use cms\models\tables\PropsTable;
use cms\services\prop\PropService;
?>
<?php echo F::form()->inputHidden('refer')?>
<div class="form-field">
    <label class="title bold">错误码</label>
    <?php echo F::form()->inputText('code', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">错误描述</label>
    <?php echo F::form()->textarea('description', array(
        'class'=>'form-control h90 autosize',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">解决方案</label>
    <?php echo F::form()->textarea('solution', array(
        'class'=>'form-control h90 autosize',
    ))?>
</div>