<?php
use fay\helpers\HtmlHelper;
use cms\services\OptionService;
use cms\services\file\FileService;
?>
<form id="options-form" class="site-settings-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <label class="title">站点名称</label>
                <?php echo HtmlHelper::inputText('site:sitename', OptionService::get('site:sitename'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">版权信息</label>
                <?php echo HtmlHelper::inputText('site:copyright', OptionService::get('site:copyright'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">备案信息</label>
                <?php echo HtmlHelper::inputText('site:beian', OptionService::get('site:beian'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">电话</label>
                <?php echo HtmlHelper::inputText('site:phone', OptionService::get('site:phone'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">传真</label>
                <?php echo HtmlHelper::inputText('site:fax', OptionService::get('site:fax'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">电子邮箱</label>
                <?php echo HtmlHelper::inputText('site:email', OptionService::get('site:email'), array(
                    'class'=>'form-control',
                    'data-rule'=>'email',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">地址</label>
                <?php echo HtmlHelper::inputText('site:address', OptionService::get('site:address'), array(
                    'class'=>'form-control',
                ))?>
            </div>
        </div>
        <div class="col-6">
            <div class="form-field">
                <label class="title">首页Title</label>
                <?php echo HtmlHelper::inputText('site:seo_index_title', OptionService::get('site:seo_index_title'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">首页Keywords</label>
                <?php echo HtmlHelper::textarea('site:seo_index_keywords', OptionService::get('site:seo_index_keywords'), array(
                    'class'=>'form-control h90 autosize',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">首页Description</label>
                <?php echo HtmlHelper::textarea('site:seo_index_description', OptionService::get('site:seo_index_description'), array(
                    'class'=>'form-control h90 autosize',
                ))?>
            </div>
            <div class="form-field">
                <label class="title">Logo</label>
                <div id="logo-container" class="mb10">
                    <a href="javascript:;" id="upload-logo" class="btn">上传Logo</a>
                </div>
                <div id="logo-preview-container"><?php
                    echo F::form()->inputHidden('site:logo', array(), 0);
                    $logo = OptionService::get('site:logo');
                    if(!empty($logo)){
                        echo HtmlHelper::link(HtmlHelper::img($logo, FileService::PIC_ORIGINAL, array()), 'javascript:;', array(
                            'encode'=>false,
                            'class'=>'block',
                            'title'=>false,
                        ));
                        echo HtmlHelper::link('移除Logo', 'javascript:;', array(
                            'class'=>'remove-image-link'
                        ));
                    }
                    ?></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:;" id="options-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>