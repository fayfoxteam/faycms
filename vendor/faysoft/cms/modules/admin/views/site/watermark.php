<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 */
?>
<form id="watermark-form" class="site-watermark-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <label class="title bold">水印类型</label>
                <?php
                    $type = OptionService::get('watermark:type');
                    echo HtmlHelper::inputRadio(
                        'watermark:type',
                        'image',
                        $type != 'text',
                        array(
                            'label'=>'图片',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:type',
                        'text',
                        $type == 'text',
                        array(
                            'label'=>'文字',
                        )
                    );
                ?>
            </div>
            <div id="watermark-form-image-panel" class="<?php if($type == 'text') echo 'hide'?>">
                <div class="form-field">
                    <label class="title bold">水印图片</label>
                    <?php $this->renderPartial('file/_upload_image', array(
                        'field'=>'watermark:image',
                        'label'=>'水印图片',
                        'remove_text'=>'',
                        'preview_image_width'=>0,
                        'field_value'=>OptionService::get('watermark:image'),
                        'default_preview_image'=>$this->assets('images/watermark.png'),
                        'ignore_fancybox'=>true,
                    ))?>
                </div>
            </div>
            <div id="watermark-form-text-panel" class="<?php if($type != 'text') echo 'hide'?>">
                <div class="form-field">
                    <label class="title bold">水印文字</label>
                    <?php echo HtmlHelper::inputText('watermark:text', OptionService::get('watermark:text', 'faycms.com'), array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字颜色</label>
                    <?php echo HtmlHelper::inputText('watermark:color', OptionService::get('watermark:color', '#FFFFFF'), array(
                        'class'=>'form-control mw200 color-picker',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字大小</label>
                    <?php echo HtmlHelper::inputText('watermark:size', OptionService::get('watermark:size', '20'), array(
                        'class'=>'form-control mw200',
                    ))?>
                </div>
            </div>
            <div class="form-field">
                <label class="title bold">水印透明度</label>
                <?php echo HtmlHelper::inputText('watermark:opacity', OptionService::get('watermark:opacity', '100'), array(
                    'class'=>'form-control mw200',
                ))?>
                <p class="fc-grey">取值：0-100，数值越小，透明度越高，0表示完全透明，100表示不透明。</p>
            </div>
            <div class="form-field">
                <label class="title bold">水平位置<em class="required">*</em></label>
                <?php
                    $align = OptionService::get('watermark:align');
                    echo HtmlHelper::inputRadio(
                        'watermark:align',
                        'left',
                        $align == 'left',
                        array(
                            'label'=>'左',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:align',
                        'center',
                        $align == 'center',
                        array(
                            'label'=>'中',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:align',
                        'right',
                        $align != 'left' && $align != 'center',
                        array(
                            'label'=>'右',
                        )
                    );
                ?>
            </div>
            <div class="form-field">
                <label class="title bold">垂直位置<em class="required">*</em></label>
                <?php
                    $valign = OptionService::get('watermark:valign', 'bottom');
                    echo HtmlHelper::inputRadio(
                        'watermark:valign',
                        'top',
                        $valign == 'top',
                        array(
                            'label'=>'上',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:valign',
                        'center',
                        $valign == 'center',
                        array(
                            'label'=>'中',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:valign',
                        'bottom',
                        $valign != 'top' && $valign != 'center',
                        array(
                            'label'=>'下',
                        )
                    );
                ?>
            </div>
            <div class="form-field">
                <label class="title bold">边距</label>
                <?php echo HtmlHelper::inputText('watermark:margin', OptionService::get('watermark:margin', 10), array(
                    'class'=>'form-control mw200',
                ))?>
            </div>
        </div>
        <div class="col-6">
            <div class="form-field">
                <label class="title bold">水印效果预览</label>
                <img src="" id="watermark-preview">
            </div>
        </div>
    </div>
    <div class="form-field">
        <a href="javascript:" id="watermark-form-submit" class="btn">提交保存</a>
    </div>
</form>
<?php $this->appendCss($this->assets('js/colpick/css/colpick.css'))?>
<script src="<?php echo $this->assets('js/colpick/js/colpick.js')?>"></script>
<script>
$(function(){
    //颜色选择器
    $('.color-picker').colpick({
        layout: 'hex',
        onChange: function(hsb,hex,rgb,el,bySetColor) {
            $(el).css('border-right','30px solid #'+hex);
            if(!bySetColor){
                $(el).val('#'+hex);
            }
            $('.color-picker').change();
        },
        onHide: function(){
            $('.color-picker').change();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value.substr(0, 1) == '#' ? this.value.substr(1) : this.value);
    }).change();
    
    //水印图预览
    var $watermarkForm = $('#watermark-form');
    $watermarkForm.on('change', 'input', function(){
        $('#watermark-preview').attr({
            'src': system.url('cms/admin/file/watermark-preview', {
                'type': $watermarkForm.find('[name="watermark:type"]:checked').val(),
                'align': $watermarkForm.find('[name="watermark:align"]:checked').val(),
                'valign': $watermarkForm.find('[name="watermark:valign"]:checked').val(),
                'opacity': $watermarkForm.find('[name="watermark:opacity"]').val(),
                'margin': $watermarkForm.find('[name="watermark:margin"]').val(),
                'text': $watermarkForm.find('[name="watermark:text"]').val(),
                'size': $watermarkForm.find('[name="watermark:size"]').val(),
                'color': $watermarkForm.find('[name="watermark:color"]').val(),
                'image': $watermarkForm.find('[name="watermark:image"]').val() ? $watermarkForm.find('[name="watermark:image"]').val() : 0
            })
        });
    });
    $watermarkForm.find('[name="watermark:type"]').change();
    
    //水印类型选择
    $watermarkForm.find('[name="watermark:type"]').on('click', function(){
        if($(this).val() == 'text'){
            $('#watermark-form-text-panel').show();
            $('#watermark-form-image-panel').hide();
        }else{
            $('#watermark-form-text-panel').hide();
            $('#watermark-form-image-panel').show();
        }
    })
});
</script>