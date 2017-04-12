<?php
use fay\helpers\HtmlHelper;
use cms\services\CategoryService;

$cat = CategoryService::service()->get($config['top'], 'title,alias');
?>
<div class="widget widget-category-posts" id="widget-<?php echo HtmlHelper::encode($alias)?>">
    <div class="box-3">
        <div class="box-3-title">
            <h3><?php echo HtmlHelper::link($cat['title'], array('cat/'.$config['top']))?></h3>
        </div>
        <div class="box-3-content">
            <ul>
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