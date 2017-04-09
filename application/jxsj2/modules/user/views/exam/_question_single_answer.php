<?php
use cms\models\tables\ExamAnswersTable;
use cms\models\tables\ExamExamQuestionAnswersIntTable;

$answers = ExamAnswersTable::model()->fetchAll('question_id = '.$exam_question['question_id'], '*', 'sort');
$user_answer = ExamExamQuestionAnswersIntTable::model()->fetchRow('exam_question_id = '.$exam_question['id']);
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
            if($a['id'] == $user_answer['user_answer_id']){
                echo '<span class="color-orange pl10">[您的答案]</span>';
            }
        ?></li>
    <?php }?>
    </ul>
</div>