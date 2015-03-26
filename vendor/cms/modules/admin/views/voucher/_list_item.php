<?php
use fay\helpers\Html;
use fay\models\tables\Vouchers;
use fay\helpers\Date;
?>
<tr>
	<td>
		<?php echo $data['sn']?>
		<div class="row-actions">
			<?php echo Html::link('永久删除', array('admin/voucher/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'fc-red remove-link',
			));?>
		</div>	
	</td>
	<td>
		<?php if($data['type'] == Vouchers::TYPE_CASH){
			echo Html::link('现金卷', array('admin/voucher/index', array(
				'type'=>$data['type']
			)));
		}else if($data['type'] == Vouchers::TYPE_DISCOUNT){
			echo Html::link('折扣卷', array('admin/voucher/index', array(
				'type'=>$data['type']
			)));
		}?>
	</td>
	<td>
		<?php echo Html::link($data['title'], array('admin/voucher/index', array(
			'cat_id'=>$data['cat_id'],
		)));?>
	</td>
	<td class="col-date">
	<?php if($data['start_time']){?>
		<span class="time abbr" title="<?php echo Date::format($data['start_time'])?>">
			<?php echo date('Y-m-d', $data['start_time'])?>
		</span>
	<?php }else{?>
		<span>无</span>
	<?php }?>
	</td>
	<td class="col-date">
	<?php if($data['end_time']){?>
		<span class="time abbr" title="<?php echo Date::format($data['end_time'])?>">
			<?php echo date('Y-m-d', $data['end_time'])?>
		</span>
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
		<span class="time abbr" title="<?php echo Date::format($data['create_time'])?>">
			<?php echo Date::niceShort($data['create_time'])?>
		</span>
	</td>
</tr>