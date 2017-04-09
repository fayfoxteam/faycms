<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<ul class="products-carousel">
<?php foreach($posts as $p){?>
    <li>
        <a href="<?php echo FileService::getUrl($p['post']['thumbnail']['id'])?>" title="<?php echo HtmlHelper::encode($p['post']['title'])?>" data-lightbox="our-products">
            <span class="item-on-hover"><span class="hover-image"></span></span>
            <?php echo HtmlHelper::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
                'dw'=>300,
                'dh'=>245,
                'alt'=>HtmlHelper::encode($p['post']['title']),
            ))?>
        </a>
        <div class="products-carousel-details">
            <?php echo HtmlHelper::link($p['post']['title'], $p['post']['link'], array(
                'wrapper'=>'h3',
            ))?>
        </div>
    </li>
<?php }?>
</ul>