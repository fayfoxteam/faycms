<?php
use fay\helpers\DateHelper;
use fay\helpers\StringHelper;
use fay\helpers\HtmlHelper;
?>
<tr>
    <td><?php echo $data['spider']?></td>
    <td><?php echo HtmlHelper::link($data['url'], array('cms/admin/analyst/spiderlog', array(
        'url'=>$data['url'],
    ), false))?></td>
    <td><abbr title="<?php echo $data['user_agent']?>"><?php echo StringHelper::niceShort($data['user_agent'], 50)?></abbr></td>
    <td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
    <td class="col-time"><abbr title="<?php echo DateHelper::format($data['create_time'])?>"><?php echo DateHelper::format($data['create_time'])?></abbr></td>
</tr>