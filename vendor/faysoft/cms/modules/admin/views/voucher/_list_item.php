<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\VouchersTable;
use fay\helpers\DateHelper;
?>
<tr>
	<td>
		<?php echo $data['sn']?>
		<div class="row-actions">
			<?php echo HtmlHelper::link('永久删除', array('cms/admin/voucher/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'fc-red remove-link',
			));?>
		</div>	
	</td>
	<td>
		<?php if($data['type'] == Vouchers::TYPE_CASH){
			echo HtmlHelper::link('现金卷', array('cms/admin/voucher/index', array(
				'type'=>$data['type']
			)));
		}else if($data['type'] == Vouchers::TYPE_DISCOUNT){
			echo HtmlHelper::link('折扣卷', array('cms/admin/voucher/index', array(
				'type'=>$data['type']
			)));
		}?>
	</td>
	<td>
		<?php echo HtmlHelper::link($data['title'], array('cms/admin/voucher/index', array(
			'cat_id'=>$data['cat_id'],
		)));?>
	</td>
	<td class="col-date">
	<?php if($data['start_time']){?>
		<abbr class="time" title="<?php echo DateHelper::format($data['start_time'])?>">
			<?php echo date('Y-m-d', $data['start_time'])?>
		</abbr>
	<?php }else{?>
		<span>无</span>
	<?php }?>
	</td>
	<td class="col-date">
	<?php if($data['end_time']){?>
		<abbr class="time" title="<?php echo DateHelper::format($data['end_time'])?>">
			<?php echo date('Y-m-d', $data['end_time'])?>
		</abbr>
	<?php }else{?>
		<span>永久有效</span>
	<?php }?>
	</td>
	<td>
		<?php echo $data['counts'] == '-1' ? '无限次' : $data['counts']?>
	</td>
	<td>
		<?php echo $data['amount'];
		if($data['type'] == Vouchers::TYPE_CASH){
			echo '元';
		}else if($data['type'] == Vouchers::TYPE_DISCOUNT){
			echo '折';
		}?>
	</td>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php echo DateHelper::niceShort($data['create_time'])?>
		</abbr>
	</td>
</tr>