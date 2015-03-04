<?php
use fay\helpers\Html;
use fay\models\tables\ExamQuestions;
?>
<div class="hide">
	<div id="question-dialog" class="common-dialog">
		<div class="common-dialog-content" style="min-width:750px;">
			<h4>添加试题</h4>
			<form method="get" id="search-form">
				<div class="mb5">
					试题
					<?php echo F::form('search')->inputText('keywords', array('class'=>'w200'))?>
					|
					<?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($question_cats));?>
					|
					<?php echo F::form('search')->select('type', array(
						''=>'--类型--',
						ExamQuestions::TYPE_TRUE_OR_FALSE=>'判断题',
						ExamQuestions::TYPE_SINGLE_ANSWER=>'单选题',
						ExamQuestions::TYPE_INPUT=>'输入题',
						ExamQuestions::TYPE_MULTIPLE_ANSWERS=>'多选题',
					))?>
				</div>
				<div class="mb5">
					创建时间
					<?php echo F::form('search')->inputText('start_time', array(
						'data-rule'=>'datetime',
						'data-label'=>'时间',
						'class'=>'datetimepicker',
					));?>
					-
					<?php echo F::form('search')->inputText('end_time', array(
						'data-rule'=>'datetime',
						'data-label'=>'时间',
						'class'=>'datetimepicker',
					));?>
					<a href="javascript:;" class="btn-3" id="search-form-ajax-submit">查询</a>
				</div>
			</form>
			<table class="inbox-table">
				<thead>
					<tr>
						<th class="w30"><input type="checkbox" class="select-all" /></th>
						<th>试题</th>
						<th>分类</th>
						<th class="w70">类型</th>
						<th class="w70">分值</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			<div id="questions-list-pager" class="pager"></div>
			<a href="javascript:;" id="select-questions" class="btn-1 mt5">添加选中试题</a>
			<a href="javascript:;" class="btn-2 mt5 fancybox-close">完成选题</a>
			<div class="clear"></div>
		</div>
	</div>
</div>