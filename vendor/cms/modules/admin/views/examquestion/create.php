<?php
use fay\models\tables\ExamQuestions;
use fay\helpers\Html;

echo F::form()->open();
?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<div class="col-2-2-body-content">
				<?php $this->renderPartial('_box_question')?>
				<div class="box" id="box-answers">
					<div class="box-title">
						<h4>答案</h4>
					</div>
					<div class="box-content" id="answer-container">
						<div id="selector-panel" <?php if(isset($question['type']) && $question['type'] != ExamQuestions::TYPE_SINGLE_ANSWER)echo 'class="hide"';?>>
							<a href="javascript:;" id="create-answer-link" class="btn">添加答案</a>
							<label>
								<?php echo F::form()->inputCheckbox('rand', 1)?>
								随机排序
							</label>
							( 钩选此项，试题将随机排序 )
							<div class="dragsort-list answer-list">
								<div class="dragsort-item">
									<a class="dragsort-rm" href="javascript:;"></a>
									<a class="dragsort-item-selector"></a>
									<div class="dragsort-item-container mr10">
									<?php
										echo Html::textarea("selector_answers[first]", '', array(
											'class'=>'form-control autosize',
										));
										echo Html::inputRadio('selector_right_answers[]', 'first', false, array(
											'label'=>'正确答案',
										));
									?>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<div id="input-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestions::TYPE_INPUT)echo 'class="hide"';?>>
						<?php echo Html::textarea('input_answer', !empty($answers[0]['answer']) ? $answers[0]['answer'] : '', array(
							'class'=>'form-control h90 autosize',
						))?>
						</div>
						<div id="true-or-false-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestions::TYPE_TRUE_OR_FALSE)echo 'class="hide"';?>>
						<?php
							echo Html::inputRadio('true_or_false_answer', 1, !empty($answers[0]['is_right_answer']) ? true : false, array(
								'label'=>'正确',
							));
							echo Html::inputRadio('true_or_false_answer', 0, empty($answers[0]['is_right_answer']) ? true : false, array(
								'label'=>'错误',
							));
						?>
						</div>
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
						<a href="javascript:;" class="btn" id="form-submit">发布</a>
					</div>
					<div class="misc-pub-section">
						<strong>状态</strong>
						<?php echo F::form()->inputRadio('status', ExamQuestions::STATUS_ENABLED, array('label'=>'启用'), true)?>
						<?php echo F::form()->inputRadio('status', ExamQuestions::STATUS_DISABLED, array('label'=>'禁用'))?>
					</div>
				</div>
			</div>
			<?php $this->renderPartial('_box_type')?>
			<?php $this->renderPartial('_box_category')?>
			<?php $this->renderPartial('_box_score')?>
			<?php $this->renderPartial('_box_sort')?>
		</div>
	</div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/question.js')?>"></script>
<script>
common.filebrowserImageUploadUrl = system.url('admin/file/img-upload', {'t':'exam'});
question.type = {
	'true_or_false':<?php echo ExamQuestions::TYPE_TRUE_OR_FALSE?>,
	'single_answer':<?php echo ExamQuestions::TYPE_SINGLE_ANSWER?>,
	'input':<?php echo ExamQuestions::TYPE_INPUT?>,
	'multiple_answers':<?php echo ExamQuestions::TYPE_MULTIPLE_ANSWERS?>
};
$(function(){
	question.init();
});
</script>