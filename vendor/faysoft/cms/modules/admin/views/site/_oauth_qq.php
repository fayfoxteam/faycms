<?php
use fay\helpers\HtmlHelper;
use cms\services\OptionService;
?>
<form id="qq-form" class="site-settings-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <label class="title">是否启用<em class="required">*</em></label>
                <?php
                    echo HtmlHelper::inputRadio('oauth:qq:enabled', '1', OptionService::get('oauth:qq:enabled') == '1', array(
                        'label'=>'是',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                    echo HtmlHelper::inputRadio('oauth:qq:enabled', '0', OptionService::get('oauth:qq:enabled') === '0', array(
                        'label'=>'否',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                ?>
            </div>
            <div class="form-field">
                <label class="title">AppID<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('oauth:qq:app_id', OptionService::get('oauth:qq:app_id'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'AppID',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">AppSecret<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('oauth:qq:app_secret', OptionService::get('oauth:qq:app_secret'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'AppSecret',
                ))?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:" id="qq-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>