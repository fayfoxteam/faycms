<?php
use fayexam\models\tables\ExamAnswersTable;
use fayexam\models\tables\ExamExamQuestionAnswersIntTable;

$answers = ExamAnswersTable::model()->fetchAll('question_id = '.$exam_question['question_id']);
$user_answer = ExamExamQuestionAnswersIntTable::model()->fetchRow('exam_question_id = '.$exam_question['id']);
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
    </div>
    <ul class="exam-question-answers">
    <?php foreach($answers as $a){?>
        <li><?php 
            echo $a['answer'];
            if($a['is_right_answer']){
                echo '<span class="fc-green pl10">[正确答案]</span>';
            }
            if($a['id'] == $user_answer['user_answer_id']){
                echo '<span class="fc-orange pl10">[用户选择]</span>';
            }
        ?></li>
    <?php }?>
    </ul>
</div>