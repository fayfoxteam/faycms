<?php
use fay\models\tables\ExamQuestions;
use fay\helpers\Html;
?>
<div class="col-1">
	<form method="get" id="search-form">
		<div class="mb5">
			试题
			<?php echo F::form('search')->inputText('keywords', array('class'=>'w200'))?>
			|
			<?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($cats));?>
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
			<a href="javascript:;" class="btn-3" id="search-form-submit">查询</a>
		</div>
	</form>
	<div class="clear"></div>
	<form method="post" action="<?php echo $this->url('admin/exam-question/batch')?>" id="batch-form">
		<div class="fl mt5"><?php
			echo Html::select('batch_action', array(
				''=>'批量操作',
				'set-enabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '启用' : false,
				'set-disabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '禁用' : false,
				'delete'=>F::app()->checkPermission('admin/exam-question/delete') ? '删除' : false,
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPage();?>
		<div class="clear"></div>
		<table border="0" cellpadding="0" cellspacing="0" class="list-table">
			<thead>
				<tr>
					<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
					<th>试题</th>
					<th>分类</th>
					<th class="w50">分值</th>
					<th class="w70">类型</th>
					<th class="w50">状态</th>
					<th class="w100">创建时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><input type="checkbox" class="batch-ids-all" /></th>
					<th>试题</th>
					<th>分类</th>
					<th>分值</th>
					<th>类型</th>
					<th>状态</th>
					<th>创建时间</th>
				</tr>
			</tfoot>
			<tbody><?php $listview->showData();?></tbody>
		</table>
		<div class="fl mt5"><?php
			echo Html::select('batch_action_2', array(
				''=>'批量操作',
				'set-enabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '启用' : false,
				'set-disabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '禁用' : false,
				'delete'=>F::app()->checkPermission('admin/exam-question/delete') ? '删除' : false,
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPage();?>
	</form>
</div>