<?php
use fay\helpers\Html;
use fay\services\LogService;
use fay\helpers\StringHelper;
use fay\helpers\Date;
?>
<tr valign="top" id="link-<?php echo $data['id']?>">
	<td><strong><?php echo $data['code']?></strong>
		<div class="row-actions">
			<?php echo Html::link('快速查看', '#log-detail-dialog', array(
				'class'=>'quick-view',
				'data-id'=>$data['id'],
			))?>
		</div>
	</td>
	<td><?php echo Log::getType($data['type'])?></td>
	<td><abbr title="<?php echo Html::encode($data['data'])?>">
		<?php echo StringHelper::niceShort($data['data'], 60, true)?>
	</abbr></td>
	<td><?php echo Html::encode($data['username'])?></td>
	<td class="col-date">
		<abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
			<?php echo Date::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
</tr>