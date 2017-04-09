<?php
use fay\models\tables\ExamAnswersTable;
use fay\models\tables\ExamExamQuestionAnswersIntTable;

$answers = ExamAnswersTable::model()->fetchAll('question_id = '.$exam_question['question_id'], '*', 'sort');
$user_answers = ExamExamQuestionAnswersIntTable::model()->fetchCol('user_answer_id', 'exam_question_id = '.$exam_question['id']);
?>
<div class="bd">
    <div class="clearfix exam-question-item">
        <span class="fl"><?php echo $index+1?>、</span>
        <div class="fl"><?php echo $exam_question['question']?></div>
        <span class="fl">（得<?php echo $exam_question['score']?> 分 / 共<?php echo $exam_question['total_score']?> 分）</span>
    </div>
    <ul class="exam-question-answers">
    <?php foreach($answers as $a){?>
        <li><?php 
            echo $a['answer'];
            if($a['is_right_answer']){
                echo '<span class="color-green pl10">[正确答案]</span>';
            }
            if(in_array($a['id'], $user_answers)){
                echo '<span class="color-orange pl10">[您的答案]</span>';
            }
        ?></li>
    <?php }?>
    </ul>
</div>