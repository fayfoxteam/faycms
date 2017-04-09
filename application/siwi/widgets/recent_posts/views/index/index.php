<?php 
use cms\services\file\FileService;
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;
?>
<aside class="m-recent-posts">
    <h3>最新博文</h3>
    <ul>
    <?php foreach($posts as $p){?>
        <li class="clearfix">
            <?php 
            if($p['thumbnail']){
                echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
                    'dw'=>100,
                    'dh'=>78,
                    'alt'=>HtmlHelper::encode($p['title']),
                    'title'=>HtmlHelper::encode($p['title']),
                )), array('blog/'.$p['id']), array(
                    'encode'=>false,
                    'title'=>HtmlHelper::encode($p['title']),
                    'alt'=>HtmlHelper::encode($p['title']),
                ));
            }else{
                echo HtmlHelper::link("<img src='{$this->url()}images/no-image.jpg' width='100' height='78' />", array('blog/'.$p['id']) ,array(
                    'encode'=>false,
                    'title'=>HtmlHelper::encode($p['title']),
                    'alt'=>HtmlHelper::encode($p['title']),
                ));
            }
            echo HtmlHelper::link(StringHelper::niceShort($p['title'], 38, true), array('blog/'.$p['id']), array(
                'title'=>HtmlHelper::encode($p['title']),
                'class'=>'title',
                'encode'=>false,
            ));
            ?>
            <span class="meta">
                作者：
                <?php echo HtmlHelper::link($p['realname'], array('u/'.$p['user_id']))?>
                |
                <?php echo $p['comments']?> 评论
            </span>
        </li>
    <?php }?>
    </ul>
</aside>