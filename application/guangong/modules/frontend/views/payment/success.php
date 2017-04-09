<?php
/**
 * @var $user_count int
 * @var $this \fay\core\View
 */

$this->appendCss($this->assets('css/jquery.fancybox-1.3.4.css'));
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
        </div>
    </div>
</div>
<div class="hide">
    <div id="pay-success-dialog" class="dialog">
        <div class="dialog-content">
            <div class="sign-up-days">关羽军团欢迎你</div>
            <div class="attendance-count">你是第<span class="content"><?php echo $user_count?></span>位兵员</div>
            <div class="yin"><img src="<?php echo $this->appAssets('images/arm/guanyin.png')?>"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<script>
$(function(){
    $.fancybox(
        $('#pay-success-dialog').parent().html(),
        {
            'width': document.documentElement.clientWidth * 0.75,
            onClosed : function(){
                window.location.href = '<?php echo $this->url('arm')?>';
            }
        }
    );
});
</script>