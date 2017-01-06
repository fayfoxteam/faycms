<?php
use blog\models\tables\Bills;
use fay\helpers\DateHelper;
?><tr>
	<td><?php echo $data['realname']?></td>
	<td><?php if($data['type'] == Bills::TYPE_IN){
		echo '<span class="fc-green">进账<span>';
	}else{
		echo '<span class="fc-red">支出<span>';
	}?></td>
	<td><?php echo $data['cat_title']?></td>
	<td><?php echo $data['amount']?></td>
	<td><?php echo $data['balance']?></td>
	<td>
		<span class="time abbr" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php echo DateHelper::niceShort($data['create_time'])?>
		</span>
	</td>
</tr>