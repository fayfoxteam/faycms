<?php
use fay\helpers\HtmlHelper;
use fay\services\LogService;
use fay\helpers\StringHelper;
use fay\helpers\DateHelper;

/**
 * @var $data array
 * @var $iplocation \IpLocation
 */
?>
<tr valign="top" id="link-<?php echo $data['id']?>">
	<td><strong><?php echo $data['code']?></strong>
		<div class="row-actions">
			<?php echo HtmlHelper::link('快速查看', '#log-detail-dialog', array(
				'class'=>'quick-view',
				'data-id'=>$data['id'],
			))?>
		</div>
	</td>
	<td><?php echo LogService::getType($data['type'])?></td>
	<td><abbr title="<?php echo HtmlHelper::encode($data['data'])?>">
		<?php echo StringHelper::niceShort($data['data'], 60, true)?>
	</abbr></td>
	<td><?php echo HtmlHelper::encode($data['username'])?></td>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php echo DateHelper::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
</tr>