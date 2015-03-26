<?php
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<form id="batch-form" method="post" action="<?php echo $this->url('admin/notification/batch')?>" class="form-inline">
			<div class="fl mt5"><?php
				echo Html::select('batch_action', array(
					''=>'批量操作',
					'set-read'=>'标记为已读',
					'set-unread'=>'标记为未读',
					'delete'=>'删除',
				), '', array(
					'class'=>'form-control',
				));
				echo Html::link('提交', 'javascript:;', array(
					'id'=>'batch-form-submit',
					'class'=>'btn btn-sm ml5',
				));
			?></div>
			<?php $listview->showPager();?>
			<table class="list-table posts">
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