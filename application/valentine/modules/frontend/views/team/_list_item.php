<?php
/**
 * @var $data array
 * @var $end_time int
 * @var $access_token string
 * @var $vote int 我的投票
 */
?>
<article>
    <div class="img"><a href="<?php
        if($data['photo']){
            //已经下载到本地，从本地输出
            echo \cms\services\file\FileService::getUrl($data['photo']);
        }else{
            //还在微信服务器，通过媒体ID输出
            echo "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$data['photo_server_id']}";
        }
    ?>" data-lightbox="teams"><?php
        if($data['photo']){
            //已经下载到本地，从本地输出
            echo \fay\helpers\HtmlHelper::img($data['photo'], \cms\services\file\FileService::PIC_ORIGINAL);
        }else{
            //还在微信服务器，通过媒体ID输出
            echo \fay\helpers\HtmlHelper::img("http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$data['photo_server_id']}");
        }
    ?></a></div>
    <div class="meta">
        <a href="javascript:;"><?php echo $data['id'], '.', \fay\helpers\HtmlHelper::encode($data['name'])?></a>
    </div>
    <div class="blessing">
        <?php echo \fay\helpers\HtmlHelper::encode($data['blessing'])?>
    </div>
    <div class="vote-container"><?php
        if($vote == $data['id']){
            echo \fay\helpers\HtmlHelper::link('已投票', 'javascript:;', array(
                'class'=>'btn wp100 btn-grey vote-link',
                'data-id'=>$data['id'],
            ));
        }else{
            echo \fay\helpers\HtmlHelper::link('投票', 'javascript:;', array(
                'class'=>'btn wp100 vote-link ' . ($vote || $end_time < \F::app()->current_time ? 'btn-grey' : 'btn-blue'),
                'data-id'=>$data['id'],
                'prepend'=>'<i class="fa fa-thumbs-up"></i>',
            ));
        }
    ?></div>
    <div class="vote-count"><span><?php echo $data['votes']?></span>票</div>
</article>
