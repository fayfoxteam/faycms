<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

?>
<form id="weixin-form" class="site-settings-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <label class="title">是否启用<em class="required">*</em></label>
                <?php
                    echo HtmlHelper::inputRadio('oauth:weixin:enabled', '1', OptionService::get('oauth:weixin:enabled') == '1', array(
                        'label'=>'是',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                    echo HtmlHelper::inputRadio('oauth:weixin:enabled', '0', OptionService::get('oauth:weixin:enabled') === '0', array(
                        'label'=>'否',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                ?>
            </div>
            <div class="form-field">
                <label class="title">AppID<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('oauth:weixin:app_id', OptionService::get('oauth:weixin:app_id'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'AppID',
                ))?>
                <p class="description">格式：wx+16位数字字母组成</p>
            </div>
            <div class="form-field">
                <label class="title">AppSecret<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('oauth:weixin:app_secret', OptionService::get('oauth:weixin:app_secret'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'AppSecret',
                ))?>
                <p class="description">格式：32位数字字母组成</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:" id="weixin-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>