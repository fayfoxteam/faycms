<?php
/**
 * @var $defence array
 * @var $user_extra array
 */
?>
<div class="swiper-slide" id="arm-3">
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t1.png')?>"></div>
</div>
<div class="swiper-slide <?php if(!$defence){echo 'set-defence-slide stop-to-next';}?>" id="arm-4">
    <div class="layer defence-text"><img src="<?php echo $this->appAssets('images/arm/defence-text.png')?>"></div>
    <div class="layer map"><img src="<?php echo $this->appAssets('images/arm/map.png')?>"></div>
    <?php if($user_extra && $user_extra['military'] >= \cms\services\OptionService::get('guangong:junfei', 1100)){?>
        <?php if(!$defence){?>
            <div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
        <?php }?>
    <?php }else{?>
        <div class="layer shake go-to-sign"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
    <?php }?>
</div>
