<?php
use cms\services\post\PostService;
use fay\helpers\HtmlHelper;

foreach($posts as $p){
    echo $this->renderPartial('_panel', array(
        'id'=>$p['id'],
        'title'=>$p['title'],
        'body'=>Post::formatContent($p),
        'file_link'=>PostService::service()->getPropValueByAlias('file_link', $p['id']),
    ));
}
?>
<div class="panel">
    <div class="panel-header">
        <h2>目录</h2>
    </div>
    <div class="panel-body">
        <ul><?php foreach($cats as $c){
            echo HtmlHelper::link($c['title'].($c['description'] ? "（{$c['description']}）" : ''), array($c['alias']), array(
                'wrapper'=>'li',
            ));
        }?></ul>
    </div>
</div>