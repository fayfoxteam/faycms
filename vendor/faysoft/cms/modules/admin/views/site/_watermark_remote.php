<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 */
?>
<form id="watermark-remote-form" class="site-settings-form watermark-form" action="<?php echo $this->url('cms/admin/site/set-options')?>">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <label class="title bold">水印类型</label>
                <?php
                    $type = OptionService::get('watermark:remote:type');
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:type',
                        'image',
                        $type != 'text',
                        array(
                            'label'=>'图片',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:type',
                        'text',
                        $type == 'text',
                        array(
                            'label'=>'文字',
                        )
                    );
                ?>
            </div>
            <div class="watermark-form-image-panel <?php if($type == 'text') echo 'hide'?>">
                <div class="form-field">
                    <label class="title bold">水印图片</label>
                    <?php $this->renderPartial('file/_upload_image', array(
                        'field'=>'watermark:remote:image',
                        'label'=>'水印图片',
                        'remove_text'=>'',
                        'preview_image_width'=>0,
                        'field_value'=>OptionService::get('watermark:remote:image'),
                        'default_preview_image'=>$this->assets('images/watermark.png'),
                        'ignore_fancybox'=>true,
                    ))?>
                </div>
            </div>
            <div class="watermark-form-text-panel <?php if($type != 'text') echo 'hide'?>">
                <div class="form-field">
                    <label class="title bold">水印文字</label>
                    <?php echo HtmlHelper::inputText('watermark:remote:text', OptionService::get('watermark:remote:text', 'faycms.com'), array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字颜色</label>
                    <?php echo HtmlHelper::inputText('watermark:remote:color', OptionService::get('watermark:remote:color', '#FFFFFF'), array(
                        'class'=>'form-control mw200 color-picker',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字大小</label>
                    <?php echo HtmlHelper::inputText('watermark:remote:size', OptionService::get('watermark:remote:size', '20'), array(
                        'class'=>'form-control mw200',
                        'data-rule'=>'int',
                        'data-params'=>'{min:0}',
                        'data-label'=>'水印文字大小'
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字宽度</label>
                    <?php echo HtmlHelper::inputText('watermark:remote:max_width', OptionService::get('watermark:remote:max_width', '0'), array(
                        'class'=>'form-control mw200',
                        'data-rule'=>'int',
                        'data-params'=>'{min:0}',
                        'data-label'=>'水印文字宽度'
                    ))?>
                    <p class="fc-grey">可以配合宽度实现水印文字换行效果</p>
                </div>
                <div class="form-field">
                    <label class="title bold">水印文字行高</label>
                    <?php echo HtmlHelper::inputText('watermark:remote:line_height', OptionService::get('watermark:remote:line_height', '1.3'), array(
                        'class'=>'form-control mw200',
                        'data-rule'=>'float',
                        'data-params'=>'{min:0}',
                        'data-label'=>'水印文字宽度'
                    ))?>
                    <p class="fc-grey">当水印文字出现换行时，可设置行高</p>
                </div>
            </div>
            <div class="form-field">
                <label class="title bold">水印透明度</label>
                <?php echo HtmlHelper::inputText('watermark:remote:opacity', OptionService::get('watermark:remote:opacity', '100'), array(
                    'class'=>'form-control mw200',
                    'data-rule'=>'int',
                    'data-label'=>'水印透明度',
                    'data-params'=>'{max:100,min:0}',
                ))?>
                <p class="fc-grey">取值：0-100，数值越小，透明度越高，0表示完全透明，100表示不透明。</p>
            </div>
            <div class="form-field">
                <label class="title bold">水平位置<em class="required">*</em></label>
                <?php
                    $align = OptionService::get('watermark:remote:align');
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:align',
                        'left',
                        $align == 'left',
                        array(
                            'label'=>'左',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:align',
                        'center',
                        $align == 'center',
                        array(
                            'label'=>'中',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:align',
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
                    $valign = OptionService::get('watermark:remote:valign', 'bottom');
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:valign',
                        'top',
                        $valign == 'top',
                        array(
                            'label'=>'上',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:valign',
                        'center',
                        $valign == 'center',
                        array(
                            'label'=>'中',
                        )
                    );
                    echo HtmlHelper::inputRadio(
                        'watermark:remote:valign',
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
                <?php echo HtmlHelper::inputText('watermark:remote:margin', OptionService::get('watermark:remote:margin', 10), array(
                    'class'=>'form-control mw200',
                    'data-rule'=>'int',
                    'data-label'=>'边距',
                    'data-params'=>'{max:100,min:0}',
                ))?>
            </div>
            <div class="form-field">
                <label class="title bold">水印添加条件</label>
                <?php
                    echo HtmlHelper::inputText('watermark:remote:min_width', OptionService::get('watermark:remote:min_width', 200), array(
                        'class'=>'form-control ib mw100',
                        'data-rule'=>'int',
                        'data-label'=>'宽度',
                        'data-params'=>'{min:0}',
                    )),
                    ' x ',
                    HtmlHelper::inputText('watermark:remote:min_height', OptionService::get('watermark:remote:min_height', 30), array(
                        'class'=>'form-control ib mw100',
                        'data-rule'=>'int',
                        'data-label'=>'高度',
                        'data-params'=>'{min:0}',
                    ));
                ?>
                <p class="fc-grey">小于此尺寸的图片附件将不添加水印</p>
            </div>
        </div>
        <div class="col-6">
            <div class="form-field">
                <label class="title bold">水印效果预览</label>
                <img src="" id="watermark-remote-preview" class="mwp100 watermark-preview">
            </div>
        </div>
    </div>
    <div class="form-field">
        <a href="javascript:" id="watermark-remote-form-submit" class="btn">提交保存</a>
    </div>
</form>