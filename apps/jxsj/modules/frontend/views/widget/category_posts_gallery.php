<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<div class="box" id="<?php echo HtmlHelper::encode($widget->alias);?>">
    <div class="box-title">
        <h3><?php
            echo HtmlHelper::link('', array('cat/'.$widget->config['top']), array(
                'class'=>'more-link',
            ));
            echo HtmlHelper::encode($widget->config['title']);
        ?></h3>
    </div>
    <div class="box-content">
        <div class="st"><div class="sl"><div class="sr"><div class="sb">
            <div class="p16 clearfix">
                <div class="box-gallery-container">
                    <ul class="box-gallery">
                    <?php foreach($posts as $p){
                        echo '<li>', HtmlHelper::link(HtmlHelper::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
                            'dw'=>203,
                            'dh'=>132,
                        )), $p['post']['link'], array(
                            'encode'=>false,
                            'alt'=>$p['post']['title'],
                            'title'=>$p['post']['title'],
                        )), HtmlHelper::link($p['post']['title'], $p['post']['link'], array(
                            'class'=>'title',
                        )), '</li>';
                    }?>
                    </ul>
                </div>
            </div>
        </div></div></div></div>
    </div>
</div>