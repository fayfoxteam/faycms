<?php
use fay\models\tables\ExamQuestions;
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				试题
				<?php echo F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				))?>
				|
				<?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($cats), array(
					'class'=>'form-control',
				));?>
				|
				<?php echo F::form('search')->select('type', array(
					''=>'--类型--',
					ExamQuestions::TYPE_TRUE_OR_FALSE=>'判断题',
					ExamQuestions::TYPE_SINGLE_ANSWER=>'单选题',
					ExamQuestions::TYPE_INPUT=>'输入题',
					ExamQuestions::TYPE_MULTIPLE_ANSWERS=>'多选题',
				), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="mb5">
				创建时间
				<?php echo F::form('search')->inputText('start_time', array(
					'class'=>'form-control datetimepicker',
				));?>
				-
				<?php echo F::form('search')->inputText('end_time', array(
					'class'=>'form-control datetimepicker',
				));?>
				<?php echo F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
		<div class="clear"></div>
		<form method="post" action="<?php echo $this->url('admin/exam-question/batch')?>" id="batch-form" class="form-inline">
			<div class="fl mt5"><?php
				echo Html::select('batch_action', array(
					''=>'批量操作',
					'set-enabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '启用' : false,
					'set-disabled'=>F::app()->checkPermission('admin/exam-question/edit') ? '禁用' : false,
					'delete'=>F::app()->checkPermission('admin/exam-question/delete') ? '删除' : false,
				), '', array(
					'class'=>'form-control',
				));
				echo Html::link('提交', 'javascript:;', array(
					'id'=>'batch-form-submit',
					'class'=>'btn btn-sm ml5',
				));
			?></div>
			<?php $listview->showPager();?>
			<div class="clear"></div>
			<table class="list-table">
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
				), '', array(
					'class'=>'form-control',
				));
				echo Html::link('提交', 'javascript:;', array(
					'id'=>'batch-form-submit-2',
					'class'=>'btn btn-sm ml5',
				));
			?></div>
			<?php $listview->showPager();?>
		</form>
	</div>
</div>