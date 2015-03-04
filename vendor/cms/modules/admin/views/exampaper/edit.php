<?php
use fay\models\tables\ExamQuestions;
use fay\models\tables\ExamPapers;
?>
<form method="post" id="form" class="validform">
	<div class="col-2-2">
		<div class="col-2-2-body-sidebar" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<a href="javascript:;" class="btn-1" id="form-submit">提交</a>
					</div>
					<div class="misc-pub-section">
						<strong>是否启用？</strong>
						<?php echo F::form()->inputRadio('status', ExamPapers::STATUS_ENABLED, array('label'=>'是'), true)?>
						<?php echo F::form()->inputRadio('status', ExamPapers::STATUS_DISABLED, array('label'=>'否'))?>
					</div>
				</div>
			</div>
			<?php $this->renderPartial('_box_category')?>
			<?php $this->renderPartial('_box_total_score')?>
			<?php $this->renderPartial('_box_repeatedly')?>
			<?php $this->renderPartial('_box_time_slot')?>
		</div>
		<div class="col-2-2-body">
			<div class="col-2-2-body-content">
				<div class="titlediv">
					<label class="title-prompt-text" for="title">试卷名称</label>
					<?php echo F::form()->inputText('title', array(
						'id'=>'title',
						'class'=>'bigtxt',
					))?>
				</div>
				<?php $this->renderPartial('_box_description')?>
				<?php $this->renderPartial('_box_questions')?>
			</div>
		</div>
	</div>
</form>
<?php $this->renderPartial('_dialog')?>
<script src="<?php echo $this->url()?>js/custom/admin/paper.js"></script>
<script>
common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'exam'});
paper.types = {
	'<?php echo ExamQuestions::TYPE_TRUE_OR_FALSE?>':'判断题',
	'<?php echo ExamQuestions::TYPE_SINGLE_ANSWER?>':'单选题',
	'<?php echo ExamQuestions::TYPE_INPUT?>':'输入题',
	'<?php echo ExamQuestions::TYPE_MULTIPLE_ANSWERS?>':'多选题'
}
$(function(){
	paper.init();
});
</script>