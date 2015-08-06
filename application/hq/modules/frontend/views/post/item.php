<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\User;
use fay\models\File;
?>
    <link href="<?= $this->staticFile('css/newslist.css') ?>" rel="stylesheet" type="text/css" />


<div class="gyah-min">
    <?php F::widget()->load('cat_posts') ?>
    <div class="gyah-minright">
        <div class="gyah-minrtop">
            <div class="gyah-minrtoptit gyah-minrtoptit2"><?= Html::encode($post['title']) ?></div>
            <div class="info">
                <ul>
                    <li>发布时间: <?= Date::format($post['publish_time']) ?></li>
                    <li>阅读数: <?= $post['views'] ?></li>
                </ul>
            </div>
            <div class="gyah-mt2pictxt">
                <p>
                   <?= $post['content'];  ?>
                </p>
                <div class="download">
                    <?php

                    if(!empty($post['files'])){
                        echo "附件：";
                        foreach($post['files'] as $k => $f){
                            $k++;
                            echo "<div class='files'>".$k.". ";
                            echo Html::link($f['description'], array('file/download', array(
                                'id'=>$f['file_id'],
                                'name'=>'date',
                            )));
                            echo "<span> (下载次数:".File::model()->getDownloads($f['file_id']).")</span></div>";
                        }
                    }
                    ?>


                </div>

            </div>
            <div class="share clearfix">
                <div class="fleft"><a href="javascript:window.print()" class="print">打印本页</a></div>
                <div class="fleft"><a href="javascript:window.close()" class="close">关闭窗口</a></div>
            </div>
        </div>
        <div class="fleft">
            <p>上一篇：<?php if($post['nav']['prev']){
                    echo Html::link($post['nav']['prev']['title'], array(
                        'post/'.$post['nav']['prev']['id'],
                    ));
                }else{
                    echo '没有了';
                }?></p>
            <p>下一篇：<?php if($post['nav']['next']){
                    echo Html::link($post['nav']['next']['title'], array(
                        'post/'.$post['nav']['next']['id'],
                    ));
                }else{
                    echo '没有了';
                }?></p>
        </div>

    </div>
</div>

<div class="clear-30"></div>



<script type="text/javascript">
    $(document).ready(function(){

        $(".suspend").mouseover(function() {
            $(this).stop();
            $(this).animate({width: 140}, 400);
        })

        $(".suspend").mouseout(function() {
            $(this).stop();
            $(this).animate({width: 40}, 400);
        });

    });
</script>
