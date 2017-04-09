<div class="mobile-page-header">
    <div class="container">
        <div class="row">
            <div class="logo">
                <a href=""><img src="<?php echo $this->appAssets('images/logo.png')?>" /></a>
            </div>
            <a href="javascript:;" class="toggle-mobile-menu"></a>
        </div>
    </div>
    <div class="mobile-menu-container">
        <nav class="mobile-menu">
            <?php F::widget()->load('mobile-menu')?>
        </nav>
    </div>
    <div class="mask"></div>
</div>