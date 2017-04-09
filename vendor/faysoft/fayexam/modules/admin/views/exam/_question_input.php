<?php
use fayexam\models\tables\ExamAnswersTable;
use fayexam\models\tables\ExamExamQuestionAnswerTextTable;
use fay\helpers\HtmlHelper;

$answer = ExamAnswersTable::model()->fetchRow('question_id = '.$exam_question['question_id']);
$user_answer = ExamExamQuestionAnswerTextTable::model()->fetchRow('exam_question_id = '.$exam_question['id']);
?>
<div class="bd" id="question-<?php echo $exam_question['id']?>">
    <div class="cf exam-question-item">
        <span><?php echo $index+1?>、</span>
        <span><?php echo $exam_question['question']?></span>
        <span>
            （得<em class="score"><?php echo $exam_question['score']?></em> 分
            /
            共<em class="total-score"><?php echo $exam_question['total_score']?></em> 分）
        </span>
        <?php if(F::app()->checkPermission('fayexam/admin/exam/set-score')){
            echo HtmlHelper::link('设置得分', 'javascript:;', array(
                'data-id'=>$exam_question['id'],
                'class'=>'set-score-link',
            ));
        }?>
    </div>
    <ul class="exam-question-answers">
        <li><span class="fl bold mr10">参考答案：</span><?php echo HtmlHelper::encode($answer['answer'])?></li>
        <li><span class="fl bold mr10">用户答案：</span><?php echo HtmlHelper::encode($user_answer['user_answer'])?></li>
    </ul>
</div>