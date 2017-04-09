<?php
use fay\helpers\HtmlHelper;
use cms\services\CategoryService;
use fay\helpers\StringHelper;

$cat = CategoryService::service()->get($config['top'], 'title,alias');
?>
<div class="widget widget-category-posts" id="widget-<?php echo HtmlHelper::encode($alias)?>">
    <div class="box">
        <div class="box-title">
            <?php echo HtmlHelper::link('more..', array('cat/' . $config['top']), array(
                'class'=>'more-link',
            ))?>
            <h3><?php echo HtmlHelper::encode($cat['title'])?></h3>
            <em><?php echo str_replace('_', ' ', $cat['alias'])?></em>
        </div>
        <div class="box-content">
            <div class="box-top-news">
                <?php $first_post = array_shift($posts);?>
                <h4><?php echo HtmlHelper::link($first_post['title'], array('post/'.$first_post['id']))?></h4>
                <p>
                    <?php echo HtmlHelper::encode(StringHelper::niceShort($first_post['abstract'], 50))?>
                    <?php echo HtmlHelper::link('[详情]', array('post/'.$first_post['id']))?>
                </p>
            </div>
            <ul class="box-news-list">
            <?php foreach($posts as $p){
                echo HtmlHelper::link('<span>'.HtmlHelper::encode($p['title']).'</span>', array('post/'.$p['id']), array(
                    'title'=>HtmlHelper::encode($p['title']),
                    'encode'=>false,
                    'wrapper'=>'li',
                    'append'=>array(
                        'tag'=>'time',
                        'text'=>$p['format_publish_time'],
                    ),
                ));
            }?>
            </ul>
        </div>
    </div>
</div>