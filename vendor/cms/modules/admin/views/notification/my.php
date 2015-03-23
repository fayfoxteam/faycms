<?php
use fay\helpers\Html;
?>
<div class="col-1">
	<form id="batch-form" method="post" action="<?php echo $this->url('admin/notification/batch')?>">
		<div class="fl mt5"><?php
			echo Html::select('batch_action', array(
				''=>'批量操作',
				'set-read'=>'标记为已读',
				'set-unread'=>'标记为未读',
				'delete'=>'删除',
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPager();?>
		<table border="0" cellpadding="0" cellspacing="0" class="list-table posts">
			<thead>
				<tr>
					<th class="w20 pl11"><input type="checkbox" class="batch-ids-all" /></th>
					<th>消息</th>
					<th>分类</th>
					<th>来自</th>
					<th>发送时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="pl11"><input type="checkbox" class="batch-ids-all" /></th>
					<th>消息</th>
					<th>分类</th>
					<th>来自</th>
					<th>发送时间</th>
				</tr>
			</tfoot>
			<tbody><?php $listview->showData();?></tbody>
		</table>
		<div class="fl mt5"><?php
			echo Html::select('batch_action_2', array(
				''=>'批量操作',
				'set-read'=>'标记为已读',
				'set-unread'=>'标记为未读',
				'delete'=>'删除',
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPager();?>
	</form>
</div>