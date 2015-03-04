<?php
use fay\helpers\Date;
use fay\helpers\String;
use fay\helpers\Html;
?>
<tr>
	<td><?php echo $data['spider']?></td>
	<td><?php echo Html::link($data['url'], array('admin/analyst/spiderlog', array(
		'url'=>$data['url'],
	), false))?></td>
	<td><span class="abbr" title="<?php echo $data['user_agent']?>"><?php echo String::niceShort($data['user_agent'], 50)?></span></td>
	<td><span class="abbr" title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></span></td>
	<td class="col-time"><span class="abbr" title="<?php echo Date::format($data['create_time'])?>"><?php echo Date::format($data['create_time'])?></span></td>
</tr>