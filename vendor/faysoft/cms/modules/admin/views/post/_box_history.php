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
            <p>共<span><?php echo $history_count?></span>个<a href="#history-dialog" class="ml5 show-post-history-link">历史版本</a></p>
        </div>
    </div>
</div>
<div class="hide">
    <div id="history-dialog" class="dialog">
        <div class="dialog-content w1000">
            <h4>历史版本</h4>
            <div class="row">
                <div class="col-3">
                    <ul class="history-list">
                        <li class="crt">
                            <div class="time"><abbr>2017-04-14 17:08</abbr></div>
                            <div class="user"><span>Fayfox中文测试</span></div>
                        </li>
                        <li>
                            <div class="time"><abbr>2017-04-13 17:08</abbr></div>
                            <div class="user"><span>Fayfox</span></div>
                        </li>
                        <li>
                            <div class="time"><abbr>2017-04-13 17:08</abbr></div>
                            <div class="user"><span>Fayfox中文测试</span></div>
                        </li>
                    </ul>
                </div>
                <div class="col-9">
                    <div class="post-preview">
                        <h2 class="post-title"><?php echo F::form()->getData('title')?></h2>
                        <div class="post-type">
                            <span>主分类</span>
                        </div>
                        <div class="post-info">
                            <div class="history-options">
                                <a href="javascript:" title="恢复到此版本" class="btn btn-grey post-history-revert-link" data-id=""><i class="fa fa-undo"></i></a>
                                <a href="javascript:" title="删除此版本" class="btn btn-grey post-history-remove-link" data-id=""><i class="fa fa-trash"></i></a>
                            </div>
                            <img src="http://71.fayfox.com/uploads/blog/avatar/2017/04/Joqf4-100x100.jpg" class="avatar">
                            <span class="user">Fayfox中文测试</span>
                            <time class="time" title="">2017-04-14 17:08</time>
                        </div>
                        <div class="post-body">
                            <div class="post-thumbnail"><?php echo HtmlHelper::img(F::form()->getData('thumbnail'))?></div>
                            <div class="post-abstract"><?php echo HtmlHelper::encode(F::form()->getData('abstract'))?></div>
                            <div class="post-content"><?php echo F::form()->getData('content')?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>