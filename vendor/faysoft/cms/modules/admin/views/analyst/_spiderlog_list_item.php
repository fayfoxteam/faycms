<?php
use fay\helpers\Date;
use fay\helpers\StringHelper;
use fay\helpers\Html;
?>
<tr>
	<td><?php echo $data['spider']?></td>
	<td><?php echo Html::link($data['url'], array('admin/analyst/spiderlog', array(
		'url'=>$data['url'],
	), false))?></td>
	<td><abbr title="<?php echo $data['user_agent']?>"><?php echo StringHelper::niceShort($data['user_agent'], 50)?></abbr></td>
	<td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
	<td class="col-time"><abbr title="<?php echo Date::format($data['create_time'])?>"><?php echo Date::format($data['create_time'])?></abbr></td>
</tr>