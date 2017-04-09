<?php
use fay\helpers\HtmlHelper;
use fayexam\models\tables\ExamQuestionsTable;
?>
<div class="hide">
    <div id="question-dialog" class="dialog">
        <div class="dialog-content" style="min-width:750px;">
            <h4>添加试题</h4>
            <?php echo F::form('search')->open(null, 'get', array(
                'class'=>'form-inline',
            ))?>
                <div class="mb5">
                    试题
                    <?php echo F::form('search')->inputText('keywords', array(
                        'class'=>'form-control w200',
                    ))?>
                    |
                    <?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+HtmlHelper::getSelectOptions($question_cats), array(
                        'class'=>'form-control',
                    ));?>
                    |
                    <?php echo F::form('search')->select('type', array(
                        ''=>'--类型--',
                        ExamQuestionsTable::TYPE_TRUE_OR_FALSE=>'判断题',
                        ExamQuestionsTable::TYPE_SINGLE_ANSWER=>'单选题',
                        ExamQuestionsTable::TYPE_INPUT=>'输入题',
                        ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS=>'多选题',
                    ), array(
                        'class'=>'form-control',
                    ))?>
                </div>
                <div class="mb5">
                    创建时间
                    <?php echo F::form('search')->inputText('start_time', array(
                        'class'=>'form-control datetimepicker',
                    ));?>
                    -
                    <?php echo F::form('search')->inputText('end_time', array(
                        'class'=>'form-control datetimepicker',
                    ));?>
                    <a href="javascript:;" class="btn btn-sm" id="search-form-ajax-submit">查询</a>
                </div>
            <?php echo F::form('search')->close()?>
            <table class="inbox-table">
                <thead>
                    <tr>
                        <th class="w30"><input type="checkbox" class="select-all" /></th>
                        <th>试题</th>
                        <th>分类</th>
                        <th class="w70">类型</th>
                        <th class="w70">分值</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="questions-list-pager" class="pager"></div>
            <a href="javascript:;" id="select-questions" class="btn mt5">添加选中试题</a>
            <a href="javascript:;" class="btn btn-grey mt5 fancybox-close">完成选题</a>
            <div class="clear"></div>
        </div>
    </div>
</div>