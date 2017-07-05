<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 * @var $iplocation IpLocation
 */
?>
<tr>
    <td><?php echo $data['spider']?></td>
    <td><?php echo HtmlHelper::link($data['url'], $data['url'], array(
        'target'=>'_blank',
    ))?></td>
    <td><abbr title="<?php echo $data['user_agent']?>"><?php echo $data['user_agent']?></abbr></td>
    <td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
    <td class="col-time"><abbr title="<?php echo DateHelper::format($data['create_time'])?>"><?php echo DateHelper::format($data['create_time'])?></abbr></td>
</tr>