<?php
/**
 * @var $this \fay\core\View
 */
?>
<div class="swiper-slide" id="recruit-7">
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/recruit/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/recruit/t2.png')?>"></div>
</div>
<div class="swiper-slide bai" id="recruit-8">
    <div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
    <div class="layer guangong"><img src="<?php echo $this->appAssets('images/group/guangong.png')?>"></div>
    <div class="layer bai-ni-ma-bi-steps">
        <a href="javascript:"><img src="<?php echo $this->appAssets('images/recruit/2-3.png')?>"></a>
        <a href="javascript:"><img src="<?php echo $this->appAssets('images/recruit/2-2.png')?>"></a>
        <a href="javascript:"><img src="<?php echo $this->appAssets('images/recruit/2-1.png')?>"></a>
    </div>
    <div class="layer jiangjunshengping">
        <a href="#shengping-dialog" class="fancybox-inline"><img src="<?php echo $this->appAssets('images/recruit/jiangjunshengping.png')?>"></a>
    </div>
</div>
<script>
$(function(){
    var audio = new Audio("<?php echo $this->appAssets('music/dbe5bd2e67f9a27e623c1e8ed0f5549b.mp3')?>");
    $('.bai-ni-ma-bi-steps').on('click', 'a', function(){
        audio.play();
    });
    audio.addEventListener('timeupdate', function(){
        if(audio.duration == audio.currentTime){
            //播放结束，弹窗
            $.fancybox.open(['<div id="baiwan-dialog" class="dialog">',
                '<div class="dialog-content">',
                    '<img src="', system.url('apps/guangong/images/recruit/yibai.png'), '" class="baiwan">',
                '</div>',
            '</div>'].join(''), {
                'onClosed': function(){
                    //common.swiper.slideNext();
                }
            });
        }
    });
    common.swiper.on('SlideChangeStart', function(){
        $activeSlide = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');
        if(!$activeSlide.hasClass('bai')){
            audio.pause();
        }
    });
});
</script>
<div class="hide">
    <div id="shengping-dialog" class="dialog">
        <div class="dialog-content">
            <img src="<?php echo $this->appAssets('images/recruit/shengping.png')?>">
        </div>
    </div>
</div>
<?php $this->renderPartial('_js')?>