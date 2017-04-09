<?php
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;
?>
<tr>
    <td><a href="<?php echo $data['url']?>" target="_blank">
        <abbr title="<?php echo urldecode(HtmlHelper::encode($data['url']))?>">
            <?php echo StringHelper::niceShort(urldecode(HtmlHelper::encode($data['url'])), 100)?>
        </abbr>
    </a></td>
    <td><?php echo $data['pv']?></td>
    <td><?php echo $data['uv']?></td>
    <td><?php echo $data['ip']?></td>
    <td><span><?php echo HtmlHelper::encode($data['site_title'])?></span></td>
</tr>