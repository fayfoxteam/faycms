<div class="row">
    <div class="col-12">
        <div class="tabbable">
            <ul class="nav-tabs">
                <li class="active"><a href="#settings-options">本地上传图片</a></li>
                <li><a href="#settings-system">远程拉取图片</a></li>
            </ul>
            <div class="tab-content">
                <div id="settings-options" class="tab-pane p5">
                    <?php echo $this->renderPartial('_watermark_upload')?>
                </div>
                <div id="settings-system" class="tab-pane p5 hide">
                    <?php echo $this->renderPartial('_watermark_remote')?>
                </div>
            </div>
        </div>
    </div>
</div>
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
        var $watermarkForm = $('.watermark-form');
        $watermarkForm.on('change', 'input', function(){
            var $currentWatermarkForm = $(this).parentsUntil('.watermark-form');
            $currentWatermarkForm.find('.watermark-preview').attr({
                'src': system.url('cms/admin/file/watermark-preview', {
                    'enabled': $currentWatermarkForm.find('[name$="enabled"]:checked').val(),
                    'type': $currentWatermarkForm.find('[name$="type"]:checked').val(),
                    'align': $currentWatermarkForm.find('[name$="align"]:checked').val(),
                    'valign': $currentWatermarkForm.find('[name$="valign"]:checked').val(),
                    'opacity': $currentWatermarkForm.find('[name$="opacity"]').val(),
                    'margin': $currentWatermarkForm.find('[name$="margin"]').val(),
                    'text': $currentWatermarkForm.find('[name$="text"]').val(),
                    'size': $currentWatermarkForm.find('[name$="size"]').val(),
                    'color': $currentWatermarkForm.find('[name$="color"]').val(),
                    'max_width': $currentWatermarkForm.find('[name$="max_width"]').val(),
                    'line_height': $currentWatermarkForm.find('[name$="line_height"]').val(),
                    'image': $currentWatermarkForm.find('[name$="image"]').val() ? $currentWatermarkForm.find('[name$="image"]').val() : 0
                })
            });
        });
        $watermarkForm.find('[name$="type"]').change();

        //水印类型选择
        $watermarkForm.find('[name$="type"]').on('click', function(){
            var $currentWatermarkForm = $(this).parentsUntil('.watermark-form');
            if($(this).val() == 'text'){
                $currentWatermarkForm.find('.watermark-form-text-panel').show();
                $currentWatermarkForm.find('.watermark-form-image-panel').hide();
            }else{
                $currentWatermarkForm.find('.watermark-form-text-panel').hide();
                $currentWatermarkForm.find('.watermark-form-image-panel').show();
            }
        })
    });
</script>