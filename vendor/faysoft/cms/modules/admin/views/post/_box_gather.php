<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" id="box-gather" data-name="gather">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>采集器</h4>
    </div>
    <div class="box-content">
        <a href="javascript:" data-src="#gather-dialog" data-fancybox class="btn">采集页面</a>
        <p class="fc-grey mt5">利用jquery选择器，进行单页面采集</p>
    </div>
</div>
<div class="hide">
    <div id="gather-dialog" class="dialog">
        <div class="dialog-content">
            <h4>文章采集</h4>
            <div id="gather-form">
                <div class="form-field">
                    <label class="title bold">链接地址</label>
                    <?php echo HtmlHelper::inputText('', '', array(
                        'class'=>'form-control',
                        'id'=>'gather-url',
                    ))?>
                    <p class="description">例如：http://www.fayfox.com/about.html —— 不要忘了 http://</p>
                </div>
                <div class="form-field">
                    <label class="title bold">JQuery选择器</label>
                    <?php echo HtmlHelper::inputText('gather_rule', '', array(
                        'class'=>'form-control',
                        'id'=>'gather-rule',
                    ))?>
                    <p class="description">例如：$("<code>.post-content</code>").html()，如红色部分的选择器。</p>
                </div>
                <div class="form-field">
                    <a href="javascript:" class="btn" id="gather-form-submit-ajax">采集</a>
                    <a href="javascript:" class="btn btn-grey fancybox-close">取消</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $(document).on('click', '#gather-form-submit-ajax', function(){
        $(this).parent().append('<img src="'+system.assets('images/throbber.gif')+'" class="submit-loading" />');
        $.ajax({
            type: "GET",
            url: system.url("cms/admin/gather/get-url"),
            data: {
                'url':$("#gather-url").val()
            },
            success: function(resp){
                common.visualEditor.setData($($("#gather-rule").val(), resp).html());
                $("#gather-form-submit-ajax").parent().find("img").remove();
                $.fancybox.close();
            }
        });
    });
});
</script>