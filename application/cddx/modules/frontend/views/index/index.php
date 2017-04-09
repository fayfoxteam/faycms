<?php
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;
?>
<div class="g-mn cf">
    <div class="box cf"><?php F::widget()->load('index-slider')?></div>
    <section class="w324 fl">
        <?php F::widget()->load('index-small-slide')?>
        <div class="box" id="index-about">
            <header class="box-title">
                <?php echo HtmlHelper::link('More..', array('page-'.$about['id']), array(
                    'class'=>'more',
                ))?>
                <h3><span>校院概况</span><em></em></h3>
            </header>
            <div class="box-content">
            <?php
                $text = StringHelper::niceShort($about['abstract'], 100, true) . HtmlHelper::link('[详细]', array('page-'.$about['id']), array(
                    'class'=>'fc-red',
                ));
                echo StringHelper::nl2p($text);
            ?>
            </div>
        </div>
    </section>
    <section class="w437 fr">
        <div class="box" id="index-news">
            <header class="box-title">
                <?php echo HtmlHelper::link('More..', array('cat-'.$cat_news['id']), array(
                    'class'=>'more',
                ))?>
                <h3><span>新闻中心</span><em></em></h3>
            </header>
            <div class="box-content">
                <div class="top-news">
                    <?php
                        $top_news = array_shift($news);
                    ?>
                    <h3><?php echo HtmlHelper::link($top_news['title'], array('post-'.$top_news['id']))?></h3>
                    <p><?php echo StringHelper::niceShort($top_news['abstract'], 50), HtmlHelper::link('[详情]', array('post-'.$top_news['id']), array(
                        'class'=>'fc-red',
                    ))?></p>
                </div>
                <ul class="post-list">
                <?php foreach($news as $n){
                    echo HtmlHelper::link('<span>'.HtmlHelper::encode($n['title']).'</span>', array('post-'.$n['id']), array(
                        'wrapper'=>'li',
                        'before'=>array(
                            'tag'=>'time',
                            'text'=>date('Y-m-d', $n['publish_time']),
                        ),
                        'encode'=>false,
                        'title'=>HtmlHelper::encode($n['title']),
                    ));
                }?>
                </ul>
            </div>
        </div>
        <?php F::widget()->load('index-1')?>
    </section>
    <div id="index-ad" class="clear">
        <?php F::widget()->load('index-ad')?>
    </div>
    <section class="w324 fl">
        <?php F::widget()->load('index-2')?>
        <?php F::widget()->load('index-4')?>
    </section>
    <section class="w437 fr">
        <?php F::widget()->load('index-3')?>
        <?php F::widget()->load('index-5')?>
    </section>
</div>
<div class="g-sd">
    <?php F::widget()->load('index-notice')?>
    <img src="<?php echo $this->appAssets('images/quick-service-title.jpg')?>" id="quick-service-title" />
    <?php F::widget()->load('quick-service')?>
    <?php F::widget()->load('index-sidebar-1')?>
    <?php F::widget()->load('index-sidebar-2')?>
</div>
<script src="<?php echo $this->assets('js/jquery.kxbdmarquee.js')?>"></script>
<script>
$(function(){
    $("#widget-index-notice .kxbdMarquee").kxbdMarquee({
        'direction':'up',
        'scrollDelay':50
    });
});
</script>