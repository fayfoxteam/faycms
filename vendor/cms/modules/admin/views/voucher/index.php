<?php
use fay\helpers\Html;
use fay\models\tables\Vouchers;
?>
<div class="col-1">
	<form id="form" method="get">
		<div class="mb5">
			优惠码:
			<?php echo F::form()->inputText('sn')?>
			|
			<?php echo F::form()->select('type', array(
				''=>'--类型--',
				Vouchers::TYPE_CASH=>'现金卷',
				Vouchers::TYPE_DISCOUNT=>'折扣卷',
			))?>
			|
			<?php echo F::form()->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($cats, 'id', 'title'))?>
			<a href="javascript:;" class="btn-3" id="form-submit">查询</a>
		</div>
		<table class="list-table" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>优惠码</th>
					<th>类型</th>
					<th>分类</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>剩余次数</th>
					<th>折扣</th>
					<th>创建时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>优惠码</th>
					<th>类型</th>
					<th>分类</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>剩余次数</th>
					<th>折扣</th>
					<th>创建时间</th>
				</tr>
			</tfoot>
			<tbody>
				<?php $listview->showData();?>
			</tbody>
		</table>
		<?php echo $listview->showPager()?>
	</form>
</div>