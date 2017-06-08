<div class="form-field">
    <label class="title bold">应用名称<em class="required">*</em></label>
    <?php echo F::form()->inputText('name', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">应用描述</label>
    <?php echo F::form()->textarea('description', array(
        'class'=>'form-control h90 autosize',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">仅登录可见</label>
    <?php
        echo F::form()->inputRadio('need_login', '0', array(
            'label'=>'否',
        ), true),
        F::form()->inputRadio('need_login', '1', array(
            'label'=>'是',
        ))
    ?>
</div>