<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<div id="index-small-slide">
    <div class="big-img">
        <a href="" class="big-img-link"><img src="" /></a>
        <a href="" class="caption"></a>
    </div>
    <div class="small-img-list">
        <ul>
        <?php foreach($files as $k=>$f){
            echo HtmlHelper::link(HtmlHelper::img($f['file_id'], FileService::PIC_RESIZE, array(
                'dw'=>76,
                'dh'=>58,
                'class'=>$k ? 'ml6' : false,
            )), 'javascript:;', array(
                'encode'=>false,
                'title'=>false,
                'data-src'=>FileService::getUrl($f['file_id']),
                'data-link'=>$f['link'] ? $f['link'] : 'javascript:;',
                'data-title'=>$f['title'],
            ));
        }?>
        </ul>
    </div>
</div>
<script>
$('#index-small-slide .big-img img').attr('src', $('.small-img-list').find('a').first().attr('data-src'));
$('#index-small-slide .big-img a').attr('href', $('.small-img-list').find('a').first().attr('data-link'));
$('#index-small-slide .big-img a.caption').text($('.small-img-list').find('a').first().attr('data-title'));
$('#index-small-slide .small-img-list').on('mouseenter', 'a', function(){
    $('#index-small-slide .big-img img').stop();
    var src = $(this).attr('data-src');
    var link = $(this).attr('data-link');
    var title = $(this).attr('data-title');
    $('#index-small-slide .big-img img').fadeTo('fast', 0.66, function(){
        $('#index-small-slide .big-img img').attr('src', src);
        $('#index-small-slide .big-img a').attr('href', link);
        $('#index-small-slide .big-img a.caption').text(title);
        $(this).fadeTo('fast', 1);
    });
});
</script>