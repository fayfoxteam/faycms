<?php
use fay\models\tables\ExamAnswers;
use fay\models\tables\ExamExamQuestionAnswerText;
use fay\helpers\Html;

$answer = ExamAnswers::model()->fetchRow('question_id = '.$exam_question['question_id']);
$user_answer = ExamExamQuestionAnswerText::model()->fetchRow('exam_question_id = '.$exam_question['id']);
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
		<?php if(F::app()->checkPermission('admin/exam-exam/set-score')){
			echo Html::link('设置得分', 'javascript:;', array(
				'data-id'=>$exam_question['id'],
				'class'=>'set-score-link',
			));
		}?>
	</div>
	<ul class="exam-question-answers">
		<li><span class="fl bold mr10">参考答案：</span><?php echo Html::encode($answer['answer'])?></li>
		<li><span class="fl bold mr10">用户答案：</span><?php echo Html::encode($user_answer['user_answer'])?></li>
	</ul>
</div>