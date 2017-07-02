<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

?>
<form id="qiniu-form" class="ajax-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <label class="title">是否启用<em class="required">*</em></label>
                <?php
                    echo HtmlHelper::inputRadio('qiniu:enabled', '1', OptionService::get('qiniu:enabled') == '1', array(
                        'label'=>'是',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                    echo HtmlHelper::inputRadio('qiniu:enabled', '0', OptionService::get('qiniu:enabled') === '0', array(
                        'label'=>'否',
                        'data-required'=>'required',
                        'data-label'=>'是否启用',
                    ));
                ?>
                <p class="description">若不启用，则图片永远调用本地地址。</p>
            </div>
            <div class="form-field">
                <label class="title">accessKey<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('qiniu:accessKey', OptionService::get('qiniu:accessKey'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'accessKey',
                ))?>
                <p class="description">从七牛开发者中心-帐号-密钥获取</p>
            </div>
            <div class="form-field">
                <label class="title">secretKey<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('qiniu:secretKey', OptionService::get('qiniu:secretKey'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'secretKey',
                ))?>
                <p class="description">从七牛开发者中心-帐号-密钥获取</p>
            </div>
            <div class="form-field">
                <label class="title">bucket<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('qiniu:bucket', OptionService::get('qiniu:bucket'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'bucket',
                ))?>
                <p class="description">空间。在七牛管理平台创建</p>
            </div>
            <div class="form-field">
                <label class="title">domain<em class="required">*</em></label>
                <?php echo HtmlHelper::inputText('qiniu:domain', OptionService::get('qiniu:domain'), array(
                    'class'=>'form-control mw400',
                    'data-required'=>'required',
                    'data-label'=>'domain',
                ))?>
                <p class="description">若绑定了独立域名（在七牛管理平台设置）可指定域名。需要http://和末尾斜杠，例如：<code>http://pic.faycms.com/</code></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:" id="qiniu-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>