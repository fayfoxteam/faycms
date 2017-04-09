<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
use cms\models\tables\FilesTable;

if($data['thumbnail']){
    $img = HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
        'dw'=>211,
        'dh'=>155,
    ));
}else{
    //获取内容的第一张图片
    preg_match('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $data['content'], $matches);
    if(isset($matches[1])){
        $filename = substr(basename($matches[1]), 0, -4);
        $file = FilesTable::model()->fetchRow(array(
            'raw_name = ?'=>$filename,
        ));
        $img = HtmlHelper::img($file['id'], FileService::PIC_RESIZE, array(
            'dw'=>211,
            'dh'=>155,
        ));
    }else{
        //默认图片
        $img = HtmlHelper::img(0, FileService::PIC_ORIGINAL, array(
            'spare'=>'default',
        ));
    }
}
?>
<div class="gallery-item">
    <?php
    echo HtmlHelper::link($img, array('post/'.$data['id']), array(
        'encode'=>false,
        'title'=>HtmlHelper::encode($data['title']),
    ));
    echo HtmlHelper::link($data['title'], array('post/'.$data['id']));
    ?>
</div>