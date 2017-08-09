<?php
/**
 * @var $this \fay\core\View
 * @var $user array
 * @var $arm array
 * @var $user_extra array
 */
?>
<div class="swiper-slide" id="recruit-41">
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/recruit/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/recruit/t4.png')?>"></div>
</div>
<div class="swiper-slide" id="recruit-42">
    <div class="layer guangongdianbing"><img src="<?php echo $this->appAssets('images/recruit/guangongdianbing.png')?>"></div>
    <div class="layer user-info">
        <fieldset>
            <label>识&nbsp;别&nbsp;号</label>
            <div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText('mobile', '', array(
                'class'=>'form-control',
                'placeholder'=>'手机号为身份识别号',
            ))?></div>
        </fieldset>
        <fieldset>
            <label>军团代号</label>
            <div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText(
                'daihao',
                '',
                array(
                    'class'=>'form-control',
                ))?></div>
        </fieldset>
    </div>
    <div class="layer button">
        <?php if(!empty($user['user']['mobile'])){?>
            <a href="#jiangjunmiling-dialog" class="btn-1" id="jiangjunmiling-link">将军密令<br>报名可阅</a>
        <?php }else{?>
            <a href="#8" class="btn-1 swiper-to" data-slide="8">我要加入</a>
        <?php }?>
    </div>
</div>
<div class="hide">
    <div id="jiangjunmiling-dialog" class="dialog">
        <div class="dialog-content">
            <img src="<?php echo $this->appAssets('images/recruit/yinzhang-text.png')?>" class="yinzhang-text">
            <img src="<?php echo $this->appAssets('images/recruit/yinzhang.png')?>" class="yinzhang">
        </div>
    </div>
</div>
<?php echo $this->renderPartial('_js')?>
<script>
$('#jiangjunmiling-link').on('click', function(){
    if($('#recruit-42').find('[name="mobile"]').val() == ''){
        common.toast('识别号不能为空', 'error');
        return false;
    }else if($('#recruit-42').find('[name="mobile"]').val() == '<?php echo $user['user']['mobile']?>'){
        $('#recruit-42').find('[name="daihao"]').val('<?php echo \guangong\helpers\UserHelper::getCode(\F::app()->current_user)?>');
        common.loadFancybox(function(){
            $.fancybox.open($('#jiangjunmiling-dialog').parent().html());
        });
    }else{
        common.toast('识别号错误', 'error');
        return false;
    }
});
$('#recruit-42').find('[name="mobile"]').on('blur', function(){
    if($(this).val() == '<?php echo $user['user']['mobile']?>'){
        $('#recruit-42').find('[name="daihao"]').val('<?php echo \guangong\helpers\UserHelper::getCode(\F::app()->current_user)?>')
    }
});
<?php if(isset($user_extra['military']) && $user_extra['military'] >= \cms\services\OptionService::get('guangong:junfei', 1100)){?>

<?php }else{?>
    $('#jiangjunmiling-link').on('click', function(){
        common.toast('您还未完成注册，请加入关羽军团后领受将军密令', 'error');
    });
<?php }?>

</script>