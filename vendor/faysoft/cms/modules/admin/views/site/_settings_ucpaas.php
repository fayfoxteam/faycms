<?php
use fay\helpers\HtmlHelper;
use cms\services\OptionService;
?>
<form id="ucpaas-form" class="site-settings-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <label class="title">是否启用<em class="required">*</em></label>
                <?php
                    echo HtmlHelper::inputRadio('ucpaas:enabled', '1', OptionService::get('ucpaas:enabled') == '1', array(
                        'label'=>'是',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                    echo HtmlHelper::inputRadio('ucpaas:enabled', '0', OptionService::get('ucpaas:enabled') === '0', array(
                        'label'=>'否',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                ?>
                <p class="description">若不启用，则调用<code>cms\services\Sms::send()</code>时直接返回true，不会真的发出短信。</p>
            </div>
            <div class="form-field">
                <label class="title">Account Sid<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('ucpaas:accountsid', OptionService::get('ucpaas:accountsid'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'Account Sid',
                ))?>
                <p class="description">从云之讯开放平台获取</p>
            </div>
            <div class="form-field">
                <label class="title">Auth Token<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('ucpaas:token', OptionService::get('ucpaas:token'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'Auth Token',
                ))?>
                <p class="description">从云之讯开放平台获取</p>
            </div>
            <div class="form-field">
                <label class="title">APP ID<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('ucpaas:appid', OptionService::get('ucpaas:appid'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'APP ID',
                ))?>
                <p class="description">从云之讯开放平台获取</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:;" id="ucpaas-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>