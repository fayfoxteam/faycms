<?php
/**
 * @var $hour array
 */
?>
<div class="swiper-slide" id="arm-7">
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t3.png')?>"></div>
</div>
<div class="swiper-slide <?php if(!$hour){echo 'set-hour-slide stop-to-next';}?>" id="arm-8">
    <?php if($hour){?>
        <a class="layer result fancybox-inline" href="#hour-dialog"><span class="hour"><?php echo $hour['name']?></span></a>
    <?php }else{?>
        <div class="layer qiantong shake"><img src="<?php echo $this->appAssets('images/arm/qiantong.png')?>"></div>
    <?php }?>
</div>