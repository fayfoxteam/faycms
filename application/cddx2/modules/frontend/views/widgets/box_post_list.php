<?php
use fay\helpers\HtmlHelper;
use fay\services\CategoryService;

$cat = CategoryService::service()->get($config['top'], 'title,alias');
?>
<div class="widget widget-category-posts<?php if($_index)echo ' area-index-' . $_index?>" id="widget-<?php echo HtmlHelper::encode($alias)?>">
    <div class="box">
        <div class="box-title">
            <?php echo HtmlHelper::link('more..', array('cat/' . $config['top']), array(
                'class'=>'more-link',
            ))?>
            <h3><?php echo HtmlHelper::encode($cat['title'])?></h3>
            <em><?php echo str_replace('_', ' ', $cat['alias'])?></em>
        </div>
        <div class="box-content">
            <ul class="box-post-list">
            <?php foreach($posts as $p){
                echo HtmlHelper::link('<span>'.HtmlHelper::encode($p['title']).'</span>', array('post/'.$p['id']), array(
                    'title'=>HtmlHelper::encode($p['title']),
                    'encode'=>false,
                    'wrapper'=>'li',
                    'prepend'=>array(
                        'tag'=>'time',
                        'text'=>$p['format_publish_time'],
                    ),
                ));
            }?>
            </ul>
        </div>
    </div>
</div>