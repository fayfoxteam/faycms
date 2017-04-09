<?php
use fay\helpers\HtmlHelper;
use cms\services\OptionService;
?>
<form id="form" action="" method="post" class="validform">
    <div class="form-field">
        <label class="title">站点名称</label>
        <?php echo HtmlHelper::inputText('site:sitename', OptionService::get('site:sitename'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
        ))?>
    </div>
    <div class="form-field">
        <label class="title">版权信息</label>
        <?php echo HtmlHelper::inputText('site:copyright', OptionService::get('site:copyright'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
        ))?>
    </div>
    <div class="form-field">
        <label class="title">电话</label>
        <?php echo HtmlHelper::inputText('site:phone', OptionService::get('site:phone'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
        ))?>
    </div>
    <div class="form-field">
        <label class="title">传真</label>
        <?php echo HtmlHelper::inputText('site:fax', OptionService::get('site:fax'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
        ))?>
    </div>
    <div class="form-field">
        <label class="title">电子邮箱</label>
        <?php echo HtmlHelper::inputText('site:email', OptionService::get('site:email'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
            'datatype'=>'e',
        ))?>
    </div>
    <div class="form-field">
        <label class="title">公司地址</label>
        <?php echo HtmlHelper::inputText('site:address', OptionService::get('site:address'), array(
            'class'=>'w300',
            'ignore'=>'ignore',
        ))?>
    </div>
    <div class="form-field">
        <a href="javascript:;" class="btn-1" id="form-submit">提交保存</a>
    </div>
</form>