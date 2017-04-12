<div class="form-field">
    <label class="title bold">名称<em class="required">*</em></label>
    <?php echo F::form()->inputText('name', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">AppID<em class="required">*</em></label>
    <?php echo F::form()->inputText('app_id', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">AppSecret<em class="required">*</em></label>
    <?php echo F::form()->inputText('app_secret', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">类型</label>
    <?php echo F::form()->select('code', \fayoauth\models\tables\OauthAppsTable::$codes + array(
            'other'=>'其它',
        ), array(
        'class'=>'form-control mw150',
    ));?>
</div>
<div class="form-field">
    <label class="title bold">别名</label>
    <?php echo F::form()->inputText('alias', array(
        'class'=>'form-control',
    ))?>
    <p class="fc-grey">别名必须唯一，可根据别名获取app信息</p>
</div>
<div class="form-field">
    <label class="title bold">是否启用</label>
    <?php
        echo F::form()->inputRadio('enabled', 1, array(
            'label'=>'是',
        ), true);
        echo F::form()->inputRadio('enabled', 0, array(
            'label'=>'否',
        ));
    ?>
</div>
<div class="form-field">
    <label class="title bold">描述</label>
    <?php echo F::form()->textarea('description', array(
        'class'=>'form-control h90 autosize',
    ))?>
</div>