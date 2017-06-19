<?php
use cms\services\post\PostHistoryService;
use fay\helpers\HtmlHelper;

if(\F::form()->getData('id')){
    $history_count = PostHistoryService::service()->getCount(\F::form()->getData('id'));
}else{
    $history_count = 0;
}
?>
<div class="box" id="box-history" data-name="history">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>历史版本</h4>
    </div>
    <div class="box-content">
        共<span><?php echo $history_count?></span>个
        <?php
            if($history_count){
                echo HtmlHelper::link('历史版本', 'javascript:', array(
                    'data-src'=>'#history-dialog',
                    'class'=>'ml5 show-post-history-link',
                ));
            }
        ?>
    </div>
</div>
<div class="hide">
    <div id="history-dialog" class="dialog">
        <div class="dialog-content w1000">
            <h4>历史版本</h4>
            <div class="row">
                <div class="col-3">
                    <ul class="history-list"></ul>
                </div>
                <div class="col-9">
                    <iframe id="history-preview" class="wp100 h500" src=""></iframe>
                </div>
            </div>
        </div>
    </div>
</div>