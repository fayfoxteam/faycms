<?php
/**
 * @var $arm array
 */
?>
<div class="swiper-slide" id="arm-5">
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t2.png')?>"></div>
</div>
<div class="swiper-slide <?php if(!$arm){echo 'set-arm-slide stop-to-next';}?>" id="arm-6">
    <?php if($arm){?>
        <a class="layer result fancybox-inline" href="#arm-dialog"><img src="<?php echo $arm['picture']['url']?>"></a>
    <?php }else{?>
        <div class="layer arms"><img src="<?php echo $this->appAssets('images/arm/arms.png')?>"></div>
        <div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
        <div class="layer langan"><img src="<?php echo $this->appAssets('images/arm/langan.png')?>"></div>
        <div class="arm-names">
            <div class="layer bubing-text"><img src="<?php echo $this->appAssets('images/arm/bubing-text.png')?>"></div>
            <div class="layer shuijun-text"><img src="<?php echo $this->appAssets('images/arm/shuijun-text.png')?>"></div>
            <div class="layer qibing-text"><img src="<?php echo $this->appAssets('images/arm/qibing-text.png')?>"></div>
            <div class="layer nubing-text"><img src="<?php echo $this->appAssets('images/arm/nubing-text.png')?>"></div>
            <div class="layer chebing-text"><img src="<?php echo $this->appAssets('images/arm/chebing-text.png')?>"></div>
        </div>
    <?php }?>
    <div class="layer arm-text"><img src="<?php echo $this->appAssets('images/arm/arm-text.png')?>"></div>
</div>