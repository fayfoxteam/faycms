<?php
/**
 * @var $ranks array
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/ranks.css'));
?>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide" id="ranks">
            <div class="layer rank-bg"><img src="<?php echo $this->appAssets('images/arm/guanzhi.png')?>"></div>
            <div class="layer rank-links">
                <a href="#rank-dialog" data-id="1" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="2" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="3" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="4" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="5" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="6" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="7" class="rank-dialog-link"></a>
                <a href="#rank-dialog" data-id="8" class="rank-dialog-link"></a>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->renderPartial('arm/_rank_dialog');
?>