<?php
use fay\helpers\Html;
use fay\models\tables\ExamQuestions;
?>

<div class="box" id="box-questions">
	<div class="box-title">
		<h4>试题</h4>
	</div>
		<div class="box-content" id="questions-container">
			<a href="#question-dialog" id="select-question-link" class="btn">选择试题</a>
			<label>
				<?php echo F::form()->inputCheckbox('rand', 1)?>
				随机排序
			</label>
			<div class="dragsort-list" id="question-list">
			<?php if(!empty($questions)){?>
				<?php foreach($questions as $q){?>
					<div class="dragsort-item">
						<?php echo Html::inputHidden('questions[]', $q['question_id'])?>
						<a class="dragsort-rm" href="javascript:;"></a>
						<a class="dragsort-item-selector"></a>
						<div class="dragsort-item-container mr10">
							<p><?php echo $q['question']?></p>
							<p class="mt5">
								<span><?php switch($q['type']){
									case ExamQuestions::TYPE_SINGLE_ANSWER:
										echo '单选题';
										break;
									case ExamQuestions::TYPE_MULTIPLE_ANSWERS:
										echo '多选题';
										break;
									case ExamQuestions::TYPE_INPUT:
										echo '输入题';
										break;
									case ExamQuestions::TYPE_TRUE_OR_FALSE:
										echo '判断题';
										break;
								}?></span>
								|
								<label>分值：<?php echo Html::inputText('score[]', $q['score'], array(
									'class'=>'form-control mw100 ib',
								))?></label>
							</p>
						</div>
					</div>
				<?php }?>
			<?php }?>
		</div>
	</div>
</div>