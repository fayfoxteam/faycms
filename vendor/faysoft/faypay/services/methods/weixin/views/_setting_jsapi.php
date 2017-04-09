<div class="form-field">
    <label class="title bold">App ID<em class="required">*</em></label>
    <?php echo F::form('payment')->inputText('config[app_id]', array(
        'class'=>'form-control mw400',
        'data-required'=>'required',
        'data-label'=>'App ID'
    ))?>
    <p class="description">
        绑定支付的AppId（必须配置，开户邮件中可查看）<br>
        格式：wx+16位数字字母组成，可登录<a href="https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN" target="_blank">公众平台</a>，进入开发者中心查看
    </p>
</div>
<div class="form-field">
    <label class="title bold">App Secret<em class="required">*</em></label>
    <?php echo F::form('payment')->inputText('config[app_secret]', array(
        'class'=>'form-control mw400',
        'data-required'=>'required',
        'data-label'=>'App Secret'
    ))?>
    <p class="description">
        公众帐号Secret<br>
        格式：32位数字字母组成，可登录<a href="https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN" target="_blank">公众平台</a>，进入开发者中心重置
    </p>
</div>
<div class="form-field">
    <label class="title bold">Mch ID<em class="required">*</em></label>
    <?php echo F::form('payment')->inputText('config[mch_id]', array(
        'class'=>'form-control mw400',
        'data-required'=>'required',
        'data-label'=>'商户号'
    ))?>
    <p class="description">商户号（必须配置，开户邮件中可查看）</p>
</div>
<div class="form-field">
    <label class="title bold">Key<em class="required">*</em></label>
    <?php echo F::form('payment')->inputText('config[key]', array(
        'class'=>'form-control mw400',
        'data-required'=>'required',
        'data-label'=>'商户支付密钥'
    ))?>
    <p class="description">商户支付密钥，参考开户邮件设置（必须配置，登录<a href="https://pay.weixin.qq.com/index.php/account/api_cert" target="_blank">商户平台</a>自行设置）</p>
</div>