<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;

?>
<tr>
    <?php if(in_array('area', $cols)){?>
    <td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
    <?php }?>
    <?php if(in_array('ip', $cols)){?>
    <td><?php echo long2ip($data['ip_int'])?></td>
    <?php }?>
    <?php if(in_array('url', $cols)){?>
    <td><a href="<?php echo $data['url']?>" target="_blank">
        <abbr title="<?php echo urldecode(HtmlHelper::encode($data['url']))?>">
            <?php echo StringHelper::niceShort(urldecode(HtmlHelper::encode($data['url'])), 32)?>
        </abbr>
    </a></td>
    <?php }?>
    <?php if(in_array('create_time', $cols)){?>
    <td><abbr title="<?php echo DateHelper::format($data['create_time'])?>">
        <?php echo DateHelper::niceShort($data['create_time'])?>
    </abbr></td>
    <?php }?>
    <?php if(in_array('site', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['site_title'])?></span></td>
    <?php }?>
    <?php if(in_array('trackid', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['trackid'])?></span></td>
    <?php }?>
    <?php if(in_array('refer', $cols)){?>
    <td><a href="<?php echo $data['refer']?>" target="_blank">
        <abbr title="<?php echo urldecode(HtmlHelper::encode($data['refer']))?>">
        <?php echo StringHelper::niceShort(urldecode(HtmlHelper::encode($data['refer'])), 32)?>
        </abbr>
    </a></td>
    <?php }?>
    <?php if(in_array('se', $cols)){?>
    <td><?php echo HtmlHelper::encode($data['se'])?></td>
    <?php }?>
    <?php if(in_array('keywords', $cols)){?>
    <td><?php echo HtmlHelper::encode($data['keywords'])?></td>
    <?php }?>
    <?php if(in_array('browser', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['browser'])?></span></td>
    <?php }?>
    <?php if(in_array('browser_version', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['browser_version'])?></span></td>
    <?php }?>
    <?php if(in_array('shell', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['shell'])?></span></td>
    <?php }?>
    <?php if(in_array('shell_version', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['shell_version'])?></span></td>
    <?php }?>
    <?php if(in_array('os', $cols)){?>
    <td><span><?php echo HtmlHelper::encode($data['os'])?></span></td>
    <?php }?>
    <?php if(in_array('ua', $cols)){?>
    <td><?php echo HtmlHelper::encode($data['user_agent'])?></td>
    <?php }?>
    <?php if(in_array('screen', $cols)){?>
    <td><span><?php echo $data['screen_width'], ' x ', $data['screen_height']?></span></td>
    <?php }?>
</tr>