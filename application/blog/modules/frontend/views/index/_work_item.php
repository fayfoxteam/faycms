<?php
use fay\services\post\PostService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;

preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $data['content'], $matches);
$post_cats = PostService::service()->getCats($data['id']);
$work_files = array();
$i = 0;
foreach($matches[1] as $m){
    if($i++ == 8){
        break;
    }
    preg_match('/\/f\/(.*?)\//', $m, $f);
    $work_files[] = $f[1];
}
?>
<article class="post-list-item">
    <div class="post-title">
        <h1>
            <a href="<?php echo $this->url('work/'.$data['id'])?>"><?php echo $data['title']?></a>
        </h1>
        <span class="post-meta">
            发表于 
            <time><?php echo DateHelper::format($data['publish_time'])?></time>
        </span>
        <div class="clear"></div>
    </div>
    <div class="post-content">
        <div class="post-abstract"><?php echo $data['abstract']?></div>
    <?php foreach($work_files as $wf){?>
        <div class="work-file-item">
            <a href="<?php echo $this->url('file/pic', array('t'=>1, 'f'=>$wf))?>">
                <?php echo HtmlHelper::img($wf, FileService::PIC_RESIZE, array(
                    'dw'=>147,
                    'dh'=>147,
                ))?>
            </a>
        </div>
    <?php }?>
        <div class="clear"></div>
    </div>
    <div class="post-tags">
        <?php
        echo HtmlHelper::link('<span>#'.HtmlHelper::encode($data['cat_title']).'</span>', array('cat/'.$data['cat_id']), array(
            'class'=>'post-type',
            'title'=>HtmlHelper::encode($data['cat_title']),
            'encode'=>false,
        ));
        foreach($post_cats as $pc){
            echo HtmlHelper::link('<span>#'.HtmlHelper::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
                'class'=>'post-type',
                'title'=>HtmlHelper::encode($pc['title']),
                'encode'=>false,
            ));
        }
        echo HtmlHelper::link('查看详细', array('work/'.$data['id']), array(
            'class'=>'post-more-link',
        ));
        ?>
        <div class="clear"></div>
    </div>
</article>