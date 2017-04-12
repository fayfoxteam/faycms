<div class="form-field">
    <label class="title bold">名称<em class="required">*</em></label>
    <?php echo F::form()->inputText('name', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">类型<em class="required">*</em></label>
    <?php echo F::form()->select('code', \fayoauth\models\tables\OauthAppsTable::$codes, array(
        'class'=>'form-control',
    ));?>
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
    <label class="title bold">描述<em class="required">*</em></label>
    <?php echo F::form()->textarea('description', array(
        'class'=>'form-control h90',
    ))?>
</div>