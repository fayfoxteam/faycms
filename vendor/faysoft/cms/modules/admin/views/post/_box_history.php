<?php
use cms\services\post\PostHistoryService;
use fay\helpers\DateHelper;
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
        <div>
            <p><span>创建于：</span><?php
                echo HtmlHelper::tag('abbr', array(
                    'title'=>DateHelper::format(F::form()->getData('create_time')),
                ), DateHelper::niceShort(F::form()->getData('create_time')));
            ?></p>
            <p><span>最近更新：</span><?php
                echo HtmlHelper::tag('abbr', array(
                    'title'=>DateHelper::format(F::form()->getData('update_time')),
                ), DateHelper::niceShort(F::form()->getData('update_time')));
            ?></p>
        </div>
        <div class="misc-pub-section mt6 pl0">
            <p>
                共<span><?php echo $history_count?></span>个
                <?php
                    if($history_count){
                        echo HtmlHelper::link('历史版本', 'javascript:', array(
                            'data-src'=>'#history-dialog',
                            'class'=>'ml5 show-post-history-link',
                        ));
                    }
                ?>
            </p>
        </div>
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