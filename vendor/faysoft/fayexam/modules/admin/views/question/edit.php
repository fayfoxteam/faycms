<?php
use fayexam\models\tables\ExamQuestionsTable;
use fay\helpers\HtmlHelper;
use fayexam\services\ExamService;

echo F::form()->open();
?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <?php $this->renderPartial('_box_question')?>
            <div class="box" id="box-answers">
                <div class="box-title">
                    <h4>答案</h4>
                </div>
                <div class="box-content" id="answer-container">
                    <div id="selector-panel" <?php if(isset($question['type']) && $question['type'] != ExamQuestionsTable::TYPE_SINGLE_ANSWER && $question['type'] != ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS)echo 'class="hide"';?>>
                        <a href="javascript:;" id="create-answer-link" class="btn">添加答案</a>
                        <label>
                            <?php echo F::form()->inputCheckbox('rand', 1)?>
                            随机排序
                        </label>
                        ( 钩选此项，试题将随机排序 )
                        <div class="dragsort-list answer-list">
                        <?php foreach($answers as $a){?>
                            <div class="dragsort-item">
                                <?php if(!ExamService::isAnswerExamed($a['id'])){
                                    echo HtmlHelper::link('', 'javascript:;', array(
                                        'class'=>'dragsort-rm',
                                    ));
                                }?>
                                <a class="dragsort-item-selector"></a>
                                <div class="dragsort-item-container mr10">
                                <?php
                                    echo HtmlHelper::textarea("selector_answers[{$a['id']}]", $a['answer'], array(
                                        'class'=>'form-control autosize',
                                    ));
                                    if(F::form()->getData('type') == ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS){
                                        echo HtmlHelper::inputCheckbox('selector_right_answers[]', $a['id'], $a['is_right_answer'] ? true : false, array(
                                            'label'=>'正确答案',
                                        ));
                                    }else{
                                        echo HtmlHelper::inputRadio('selector_right_answers[]', $a['id'], $a['is_right_answer'] ? true : false, array(
                                            'label'=>'正确答案',
                                        ));
                                    }
                                ?>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                    <div id="input-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestionsTable::TYPE_INPUT)echo 'class="hide"';?>>
                    <?php echo HtmlHelper::textarea('input_answer', !empty($answers[0]['answer']) ? $answers[0]['answer'] : '', array(
                        'class'=>'form-control h90 autosize',
                    ))?>
                    </div>
                    <div id="true-or-false-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestionsTable::TYPE_TRUE_OR_FALSE)echo 'class="hide"';?>>
                    <?php
                        echo HtmlHelper::inputRadio('true_or_false_answer', 1, !empty($answers[0]['is_right_answer']) ? true : false, array(
                            'label'=>'正确',
                        ));
                        echo HtmlHelper::inputRadio('true_or_false_answer', 0, empty($answers[0]['is_right_answer']) ? true : false, array(
                            'label'=>'错误',
                        ));
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box" id="box-operation">
                <div class="box-title">
                    <h4>操作</h4>
                </div>
                <div class="box-content">
                    <div>
                        <a href="javascript:;" class="btn" id="form-submit">编辑</a>
                    </div>
                    <div class="misc-pub-section mt6">
                        <strong>状态</strong>
                        <?php echo F::form()->inputRadio('status', ExamQuestionsTable::STATUS_ENABLED, array('label'=>'启用'), true)?>
                        <?php echo F::form()->inputRadio('status', ExamQuestionsTable::STATUS_DISABLED, array('label'=>'禁用'))?>
                    </div>
                </div>
            </div>
            <?php $this->renderPartial('_box_type')?>
            <?php $this->renderPartial('_box_category', array(
                'cats'=>$cats,
            ))?>
            <?php $this->renderPartial('_box_score')?>
            <?php $this->renderPartial('_box_sort')?>
        </div>
    </div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/question.js')?>"></script>
<script>
common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat':'exam'});
question.type = {
    'true_or_false':<?php echo ExamQuestionsTable::TYPE_TRUE_OR_FALSE?>,
    'single_answer':<?php echo ExamQuestionsTable::TYPE_SINGLE_ANSWER?>,
    'input':<?php echo ExamQuestionsTable::TYPE_INPUT?>,
    'multiple_answers':<?php echo ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS?>
};
$(function(){
    question.init();
});
</script>