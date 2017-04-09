<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
use fay\helpers\DateHelper;

/**
 * @var $post array 文章信息
 */
\F::app()->layout->assign(array(
    'page_title'=>$post['post']['title']
));
?>
<div class="post-item">
    <article>
        <div class="post-meta">
            <time class="post-meta-item post-meta-time"><?php
                echo DateHelper::niceShort($post['post']['publish_time'])
            ?></time>
            <?php echo HtmlHelper::link($post['category']['title'], array('cat/'.$post['category']['id']), array(
                'class'=>array('post-meta-item', 'post-meta-category'),
            ))?>
            <span class="post-meta-item post-meta-views">
                <span>阅读数</span>
                <a><?php
                    echo $post['meta']['views']
                    ?></a>
            </span>
        </div>
        <?php if($post['files']){?>
            <div class="post-featured">
                <div class="swiper-container post-files">
                    <div class="swiper-wrapper">
                    <?php foreach($post['files'] as $file){?>
                        <div class="swiper-slide">
                            <a href="<?php echo $file['url']?>" title="<?php echo HtmlHelper::encode($file['description'])?>" data-lightbox="files"><?php
                                 echo HtmlHelper::img($file['thumbnail'], FileService::PIC_ORIGINAL, array(
                                    'alt'=>$file['description'],
                                ))
                            ?></a>
                        </div>
                    <?php }?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-control-container">
                        <a class="swiper-btn-prev"></a>
                        <a class="swiper-btn-next"></a>
                    </div>
                </div>
            </div>
        <?php }?>
        <?php if(!$post['files'] && $post['post']['thumbnail']['id']){?>
            <div class="post-featured">
                <div class="post-thumb">
                    <a href="<?php echo $post['post']['thumbnail']['url']?>" data-lightbox="files"><?php
                        echo HtmlHelper::img($post['post']['thumbnail']['thumbnail']);
                    ?></a>
                </div>
            </div>
        <?php }?>
        <div class="post-container">
            <div class="post-content">
                <p><?php echo $post['post']['content']?></p>
            </div>
            <?php if($post['tags']){?>
            <div class="post-tags">
                <p>
                    <span>标签：</span>
                    <?php
                        foreach($post['tags'] as $k => $tag){
                            if($k){
                                echo ', ';
                            }
                            echo HtmlHelper::link($tag['tag']['title'], array('tag/'.urlencode($tag['tag']['title'])));
                        }
                    ?>
                </p>
            </div>
            <?php }?>
        </div>
    </article>
</div>
<script>
var post_item = {
    'lightbox': function(){
        if($('[data-lightbox]').length){
            system.getCss(system.assets('css/lightbox/css/lightbox.css'), function(){
                system.getScript(system.assets('js/lightbox.min.js'), function(){
                    lightbox.option({
                        'albumLabel': '',
                        'wrapAround': true
                    });
                });
            });
        }
    },
    'init':function(){
        this.lightbox();
    }
};
post_item.init();
</script>