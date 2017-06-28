<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

?>
<form id="link-form" class="site-settings-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <h4>文章链接格式</h4>
            </div>
            <div class="form-field">
                <?php echo HtmlHelper::inputText('system:post_uri', OptionService::get('system:post_uri', 'post/{$id}'), array(
                    'class'=>'form-control mw200',
                ))?>
                <p class="fc-grey">
                    文章相关的widget和后台文章预览链接会根据这个配置生成，可用参数如下：<br>
                    <code>{$id}</code>代表“文章ID”<br>
                    <code>{$cat_id}</code>代表“分类ID”<br>
                    <code>{$cat_alias}</code>代表“分类别名”<br>
                    <code>{$date:xx}</code>代表“发布时间”，其中xx可以是一下参数的组合（实际上是php自带date函数参数）：<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<code>Y</code>代表“发布四位年份”，例如：<?php echo date('Y')?><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<code>y</code>代表“发布两位年份”，例如：<?php echo date('y')?><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<code>m</code>代表“有前导0的月份”，例如：03<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<code>n</code>代表“没有签到0的月份”，例如：3<br>
                    不要包含base_url部分
                </p>
            </div>
        </div>
        <div class="col-6">
            <div class="form-field">
                <h4>分类页链接格式</h4>
            </div>
            <div class="form-field">
                <?php echo HtmlHelper::inputText('system:cat_uri', OptionService::get('system:cat_uri', 'cat/{$id}'), array(
                    'class'=>'form-control mw200',
                ))?>
                <p class="fc-grey">
                    分类相关的widget会根据这个配置生成，可用参数如下：<br>
                    <code>{$id}</code>代表“分类ID”<br>
                    <code>{$alias}</code>代表“分类别名”<br>
                    不要包含base_url部分
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-field">
                <a href="javascript:" id="link-form-submit" class="btn">提交保存</a>
            </div>
        </div>
    </div>
</form>