<?php
use fay\models\tables\ExamQuestions;
?>
<div class="box" id="box-type" data-name="type">
	<div class="box-title">
		<h4>类型</h4>
	</div>
	<div class="box-content">
		<p><?php echo F::form()->inputRadio('type', ExamQuestions::TYPE_TRUE_OR_FALSE, array(
			'label'=>'判断题',
			'disabled'=>empty($is_examed) ? false : 'disabled',
		));?></p>
		<p><?php echo F::form()->inputRadio('type', ExamQuestions::TYPE_SINGLE_ANSWER, array(
			'label'=>'单选题',
			'disabled'=>empty($is_examed) ? false : 'disabled',
		), true);?></p>
		<p><?php echo F::form()->inputRadio('type', ExamQuestions::TYPE_INPUT, array(
			'label'=>'填空题',
			'disabled'=>empty($is_examed) ? false : 'disabled',
		));?></p>
		<p><?php echo F::form()->inputRadio('type', ExamQuestions::TYPE_MULTIPLE_ANSWERS, array(
			'label'=>'多选题',
			'disabled'=>empty($is_examed) ? false : 'disabled',
		));?></p>
	</div>
</div>