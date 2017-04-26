<?php
/**
 * @var $posts array
 */

//获取分类描述
$cat = \cms\services\CategoryService::service()->get($widget->config['cat_id'], 'description');
?>
<section class="section" id="section-ancillary">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title-group">
                    <h2 class="title"><?php echo \fay\helpers\HtmlHelper::encode($widget->config['title'])?></h2>
                    <div class="description">
                        <p><?php echo \fay\helpers\HtmlHelper::encode($cat['description'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ancillary-list">
            <?php foreach($posts as $p){?>
                <?php $props = \fay\helpers\ArrayHelper::column($p['props'], null, 'alias')?>
                <article class="cf">
                    <div class="col-md-4 col-sm-4 image-container">
                        <img src="<?php echo $p['post']['thumbnail']['thumbnail']?>">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="ancillary-info">
                            <h3 class="title"><?php echo \fay\helpers\HtmlHelper::link($p['post']['title'], array('post/'.$p['post']['id']))?></h3>
                            <h6 class="description"><?php echo isset($props['subtitle']['value']) ? $props['subtitle']['value'] : ''?></h6>
                            <p class="text"><?php echo nl2br(\fay\helpers\HtmlHelper::encode($p['post']['abstract']))?></p>
                        </div>
                    </div>
                </article>
            <?php }?>
        </div>
    </div>
</section>